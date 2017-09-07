<?php

namespace JPCaparas\Box;

use JPCaparas\Box\Services\AuthService;
use JPCaparas\Box\Services\FileService;
use Linkstreet\Box\Auth\AppAuth;
use Linkstreet\Box\Box as BoxBase;

/**
 * Class Box
 *
 * @package JPCaparas\Box
 */
class Box
{
    /**
     * @var BoxConfig
     */
    private $config;

    /**
     * @var AppAuth
     */
    private $client;

    /**
     * @var FileService
     */
    private $fileService;

    /**
     * @var AuthService
     */
    private $authService;

    /**
     * BoxService constructor.
     *
     * @param BoxConfig $config
     */
    public function __construct(BoxConfig $config)
    {
        $this->setConfig($config);
        $this->setClient();
    }

    /**
     * @return BoxConfig
     */
    public function getConfig(): BoxConfig
    {
        return $this->config;
    }

    /**
     * @param BoxConfig $config
     */
    public function setConfig(BoxConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return AppAuth
     */
    protected function getClient(): AppAuth
    {
        return $this->client;
    }

    protected function setClient()
    {
        $config = $this->getConfig();

        $client = new BoxBase([
            'client_id'     => $config->getApiKey(),
            'client_secret' => $config->getApiSecret()
        ]);

        $config = $this->getConfig();

        $authInfo = [
            'key_id'            => $config->getAuthKeyId(),
            'private_key'       => $config->getAuthPrivateKeyPath(),
            'pass_phrase'       => $config->getAuthPassphrase(),
            'subscription_type' => $config->getAuthSubscriptionType(),
            'id'                => $config->getAppId(),
        ];

        $this->client = $client->getAppAuthClient($authInfo);
    }

    public function file(): FileService
    {
        if (is_null($this->fileService) === true) {
            $this->fileService = new FileService($this->getClient());
        }

        return $this->fileService;
    }

    public function auth(): AuthService
    {
        if (is_null($this->authService) === true) {
            $this->authService = new AuthService($this->getClient());
        }

        return $this->authService;
    }
}
