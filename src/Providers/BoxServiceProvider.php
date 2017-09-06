<?php

namespace JPCaparas\Box\Providers;

use Illuminate\Support\ServiceProvider;
use JPCaparas\Box\Box;
use JPCaparas\Box\BoxConfig;

/**
 * Class BoxServiceProvider
 *
 * @package JPCaparas\Box\Providers
 */
class BoxServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Usage: php artisan vendor:publish --provider='JPCaparas\Box\Providers\BoxServiceProvider'
        $this->publishes([
            __DIR__ . '/../../config/box.php' => $this->app->configPath() . '/box.php',
        ], 'config');
    }

    public function register()
    {
        $this->app->singleton(Box::class, function () {
            /**
             * @var array $config
             */
            $config = $this->app->config;

            $boxConfig = new BoxConfig();

            $boxConfig->setAppId($config['box.app.id']);

            $boxConfig->setApiKey($config['box.api.key']);
            $boxConfig->setApiSecret($config['box.api.secret']);

            $boxConfig->setAuthKeyId($config['box.auth.key_id']);
            $boxConfig->setAuthPrivateKeyPath($config['box.auth.private_key_path']);
            $boxConfig->setAuthPassphrase($config['box.auth.passphrase']);

            return new Box($boxConfig);
        });
    }
}
