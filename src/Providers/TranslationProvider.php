<?php

declare(strict_types=1);

namespace MiBo\Properties\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class TranslationProvider
 *
 * @package MiBo\Properties\Providers
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
class TranslationProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        $this->publishLanguages();
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
}
