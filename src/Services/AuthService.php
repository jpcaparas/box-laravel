<?php

namespace JPCaparas\Box\Services;

use Linkstreet\Box\Auth\AppAuth;
use JPCaparas\Box\Services\FileService as BaseFileService;

/**
 * Class AuthService
 *
 * @package JPCaparas\Box\Services
 */
class AuthService
{
    /**
     * @var AppAuth
     */
    private $client;

    /**
     * @var BaseFileService
     */
    private $tokenInfo;

    public function __construct($client)
    {
        $this->client = $client;

        $this->tokenInfo = $this->client->getTokenInfo();
    }

    /**
     * @return string
     */
    public function accessToken(): string
    {
        return $this->tokenInfo->access_token;
    }
}
