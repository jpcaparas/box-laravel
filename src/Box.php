<?php

namespace JPCaparas\Box;

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
     * @var BoxBase
     */
    private $client;

    /**
     * @var FileService
     */
    private $fileService;

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
        $config = $this->getConfig();

        $authInfo = [
            'key_id'            => $config->getAuthKeyId(),
            'private_key'       => $config->getAuthPrivateKeyPath(),
            'pass_phrase'       => $config->getAuthPassphrase(),
            'subscription_type' => $config->getAuthSubscriptionType(),
            'id'                => $config->getAppId(),
        ];

        return $this->client->getAppAuthClient($authInfo);
    }

    protected function setClient()
    {
        $config = $this->getConfig();
        $this->client = new BoxBase([
            'client_id'     => $config->getApiKey(),
            'client_secret' => $config->getApiSecret()
        ]);
    }

    public function file(): FileService
    {
        if (is_null($this->fileService) === true) {
            $this->fileService = new FileService($this->getClient());
        }

        return $this->fileService;
    }
}
