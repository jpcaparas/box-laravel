<?php

namespace JPCaparas\Box\Services;

use Linkstreet\Box\Auth\AppAuth;

/**
 * Interface ServiceInterface
 *
 * @package JPCaparas\Box\Services
 */
interface ServiceInterface
{
    /**
     * @return AppAuth
     */
    public function getClient() : AppAuth;
}
