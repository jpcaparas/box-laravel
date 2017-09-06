<?php

namespace JPCaparas\Box;

use Linkstreet\Box\Enums\SubscriptionType;

class BoxConfig
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $apiSecret;

    /**
     * @var string
     */
    private $appId;

    /**
     * @var string
     */
    private $authKeyId;

    /**
     * @var string
     */
    private $authSubscriptionType;

    /**
     * @var string
     */
    private $authPrivateKeyPath;

    /**
     * @var string
     */
    private $authPassphrase;

    /**
     * @return string
     */
    public function getAppId(): string
    {
        return $this->appId ?? '';
    }

    /**
     * @param string $appId
     */
    public function setAppId(string $appId)
    {
        $this->appId = $appId;
    }

    /**
     * @return string
     */
    public function getAuthKeyId(): string
    {
        return $this->authKeyId ?? '';
    }

    /**
     * @param string $authKeyId
     */
    public function setAuthKeyId(string $authKeyId)
    {
        $this->authKeyId = $authKeyId;
    }

    /**
     * @return string
     */
    public function getAuthPassphrase(): string
    {
        return $this->authPassphrase ?? '';
    }

    /**
     * @param string $authPassphrase
     */
    public function setAuthPassphrase(string $authPassphrase)
    {
        $this->authPassphrase = $authPassphrase;
    }

    /**
     * @return string
     */
    public function getAuthSubscriptionType(): string
    {
        return $this->authSubscriptionType ?? SubscriptionType::ENTERPRISE;
    }

    /**
     * @param string $authSubscriptionType
     */
    public function setAppSubscriptionType(string $authSubscriptionType)
    {
        $this->authSubscriptionType = $authSubscriptionType;
    }

    /**
     * @return string
     */
    public function getAuthPrivateKeyPath(): string
    {
        return $this->authPrivateKeyPath ?? '';
    }

    /**
     * @param string $authPrivateKeyPath
     */
    public function setAuthPrivateKeyPath(string $authPrivateKeyPath)
    {
        $this->authPrivateKeyPath = $authPrivateKeyPath;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function getApiSecret(): string
    {
        return $this->apiSecret;
    }

    /**
     * @param string $apiSecret
     */
    public function setApiSecret(string $apiSecret)
    {
        $this->apiSecret = $apiSecret;
    }
}
