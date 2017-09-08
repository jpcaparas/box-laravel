<?php

namespace JPCaparas\Box\Services;

use Assert\Assertion;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class BaseService implements ServiceInterface
{
    /**
     * @var RequestInterface
     */
    private $lastRequest;

    /**
     * @return RequestInterface
     */
    public function getLastRequest(): RequestInterface
    {
        return $this->lastRequest;
    }

    /**
     * @param RequestInterface $lastRequest
     */
    protected function setLastRequest(RequestInterface $lastRequest)
    {
        $this->lastRequest = $lastRequest;
    }

    protected function getAuthHeaders()
    {
        return [
            'Authorization' => 'Bearer ' . $this->getClient()->getTokenInfo()->access_token
        ];
    }

    /**
     * @return Client
     */
    protected function guzzleClient()
    {
        return new Client();
    }

    /**
     * Send an HTTP request
     *
     * @param string $method
     * @param string $uri
     * @param array  $options
     *
     * @return ResponseInterface
     *
     * @throws ClientException
     */
    protected function sendRequest(string $method, string $uri, array $options = [])
    {
        Assertion::choice(
            $method,
            ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'],
            'Expected a valid verb. Got %s.'
        );

        Assertion::url($uri, 'Expected a valid URL. Got %s.');

        $options = array_merge($options, ['headers' => $this->getAuthHeaders()]);

        try {
            return $this->guzzleClient()->request($method, $uri, $options);
        } catch (ClientException $e) {
            $this->setLastRequest($e->getRequest());

            throw $e;
        }
    }

    /**
     * @param string $uri
     * @param array  $options
     *
     * @return ResponseInterface
     */
    protected function sendPost(string $uri, array $options = [])
    {
        return $this->sendRequest('POST', $uri, $options);
    }

    /**
     * @param string $uri
     * @param array  $options
     *
     * @return ResponseInterface
     */
    protected function sendGet(string $uri, array $options = [])
    {
        return $this->sendRequest('GET', $uri, $options);
    }
}
