<?php

declare(strict_types=1);

namespace MiBo\Properties\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class ConfigProvider
 *
 * @package MiBo\Properties\Providers
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
class ConfigProvider extends ServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register(): void
    {
        parent::register();
        $this->publishConfigurations();
    }

    /**
     * Publishes configuration files.
     *
     * @return void
     */
    protected function publishConfigurations(): void
    {
        $this->publishes(
            [
                __DIR__ . '/../../resources/config/properties.php' =>
                    config_path('properties.php'),
                __DIR__ . '/../../resources/config/prices.php'     =>
                    config_path('prices.php'),
            ]
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../../resources/config/properties.php',
            'properties'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../../resources/config/prices.php',
            'prices'
        );
    }
}
