<?php

declare(strict_types=1);

namespace MiBo\Prices\Providers;

/**
 * Class ServiceProvider
 *
 * @package MiBo\Prices\Providers
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register(): void
    {
        parent::register();
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        $this->publishConfigurations();
        $this->publishLanguages();
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
                __DIR__ . '/../../resources/config/mibo-properties.php' =>
                    config_path('mibo' . DIRECTORY_SEPARATOR . 'properties.php'),
            ]
        );
    }

    /**
     * Publishes language files.
     *
     * @return void
     */
    protected function publishLanguages(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'properties');
        $this->publishes(
            [__DIR__ . '/../../resources/lang' => $this->app->langPath('vendor/properties')]
        );
    }

    /**
     * @inheritDoc
     *
     * @return string[]
     */
    public function provides(): array
    {
        return ["MiBoMoney"];
    }
}
