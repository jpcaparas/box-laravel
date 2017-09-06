<?php

namespace JPCaparas\Box\Services;

use GuzzleHttp\Psr7\Response;
use Linkstreet\Box\Auth\AppAuth;
use JPCaparas\Box\Services\FileService as BaseFileService;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Class FileService
 *
 * @package JPCaparas\Box\Services
 */
class FileService
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
        $this->fileService = $this->client->getFileService();
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
    public function upload(File $file, string $fileName = null, int $folderId = null)
    {
        $fileName = $fileName ?? $file->getFilename();
        $folderId = $folderId ?? 0;

        $response = $this->fileService->upload($file, $folderId, $fileName);

        $this->setLastResponse($response);

        return (int)$this->getLastResponse()['entries'][0]['id'];
    }

    /**
     * @param int $fileId
     *
     * @return String
     */
    public function embedUrl(int $fileId)
    {
        $this->setLastResponse(null);

        $response = $this->fileService->getEmbedUrl($fileId);

        $this->setLastResponse($response);

        return $response;
    }

    /**
     * @param int $fileId
     *
     * @return bool
     */
    public function delete(int $fileId)
    {
        /**
         * @var Response $response
         */
        $response = $this->fileService->delete($fileId);

        $this->setLastResponse($response);

        return $response->getStatusCode() === HttpResponse::HTTP_NO_CONTENT;
    }
}
