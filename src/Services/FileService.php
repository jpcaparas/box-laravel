<?php

namespace JPCaparas\Box\Services;

use Assert\Assertion;
use Assert\InvalidArgumentException;
use GuzzleHttp\Psr7\Response;
use Linkstreet\Box\Auth\AppAuth;
use JPCaparas\Box\Services\FileService as BaseFileService;
use Linkstreet\Box\Enums\BoxAccessPoints;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Class FileService
 *
 * @package JPCaparas\Box\Services
 */
class FileService extends BaseService
{
    /**
     * @var AppAuth
     */
    private $client;

    /**
     * @var BaseFileService
     */
    private $fileService;

    /**
     * @var mixed
     */
    private $lastResponse;

    public function __construct($client)
    {
        $this->client = $client;
        $this->fileService = $this->getClient()->getFileService();
    }

    /**
     * @return AppAuth
     */
    public function getClient(): AppAuth
    {
        return $this->client;
    }

    /**
     * @return mixed
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * @param mixed $lastResponse
     */
    protected function setLastResponse($lastResponse)
    {
        if (($lastResponse instanceof Response) === true) {
            $this->lastResponse = json_decode($lastResponse->getBody()->getContents(), true);

            return;
        }

        $this->lastResponse = $lastResponse;
    }

    /**
     * @param File        $file
     * @param string|null $fileName
     * @param int         $folderId
     *
     * @return int
     */
    public function upload(File $file, string $fileName = null, int $folderId = 0)
    {
        $fileName = $fileName ?? $file->getFilename();

        $response = $this->fileService->upload($file, $folderId, $fileName);

        $this->setLastResponse($response);

        return (int) $this->getLastResponse()['entries'][0]['id'];
    }

    /**
     * @param int|string      $fileId
     *
     * @return File
     */
    public function download($fileId)
    {
        $response = $this->fileService->download($fileId);

        return $response->getBody()->getContents();
    }

    /**
     * @param int|string      $fileId
     * @param int             $width
     * @param int             $height
     *
     * @return File
     */
    public function downloadThumbnail($fileId, int $width = 320, int $height = 320)
    {
        $response = $this->fileService->downloadThumbnail($fileId, $width, $height);

        return $response->getBody()->getContents();
    }

    /**
     * @param int|string      $fileId
     *
     * @return array
     */
    public function getFileInfo($fileId)
    {
        $response = $this->fileService->getFileInfo($fileId);

        $this->setLastResponse($response);

        return $this->getLastResponse();
    }

    /**
     * @param int $fileId
     *
     * @return \stdClass
     */
    public function get(int $fileId)
    {
        Assertion::integer($fileId, 'File ID has to be an integer. Got: %s');

        $response = $this->sendGet(
            BoxAccessPoints::BASE_FILE_URL .
            BoxAccessPoints::URL_SEPARATOR .
            $fileId
        );

        $httpResponse = $response->getBody()->getContents();
        $response = json_decode($httpResponse);

        try {
            Assertion::objectOrClass($response);
        } catch (InvalidArgumentException $e) {
            $this->setLastResponse($response);

            throw $e;
        }

        $this->setLastResponse($response);

        return $response;
    }

    /**
     * @param int    $fileId
     *
     * @return string
     */
    public function content(int $fileId)
    {
        Assertion::integer($fileId, 'File ID has to be an integer. Got: %s');

        $response = $this->sendGet(
            BoxAccessPoints::BASE_FILE_URL .
            BoxAccessPoints::URL_SEPARATOR .
            $fileId .
            BoxAccessPoints::URL_SEPARATOR .
            'content'
        );

        $response = $response->getBody()->getContents();

        $this->setLastResponse($response);

        return $response;
    }

    /**
     * @param int    $fileId
     * @param string $downloadDir
     * @param null|string $filename
     *
     * @return string
     */
    public function download(int $fileId, string $downloadDir = null, string $filename = null)
    {
        Assertion::directory($downloadDir, 'Download directory does not exist. Got %s.');
        Assertion::string($downloadDir, 'Expected file name. Got %s.');

        $info = $this->get($fileId);
        $content = $this->content($fileId);

        $filename = $filename ?? $info->name;
        $path = rtrim($downloadDir, '/') . '/' . $filename;

        file_put_contents($path, $content);

        Assertion::file($path, 'File was not saved to ' . $path . '.');

        return $path;
    }

    /**
     * @param int $fileId
     *
     * @return String
     */
    public function embedUrl($fileId)
    {
        $response = $this->fileService->getEmbedUrl($fileId);

        $this->setLastResponse($response);

        return $response;
    }

    /**
     * @param int $fileId
     *
     * @return bool
     */
    public function delete($fileId)
    {
        /**
         * @var Response $response
         */
        $response = $this->fileService->delete($fileId);

        $this->setLastResponse($response);

        return $response->getStatusCode() === HttpResponse::HTTP_NO_CONTENT;
    }
}
