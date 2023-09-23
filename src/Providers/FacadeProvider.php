<?php

declare(strict_types=1);

namespace MiBo\Prices\Providers;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use MiBo\Prices\Managers\MoneyManager;

/**
 * Class FacadeProvider
 *
 * @package MiBo\Prices\Providers
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
class FacadeProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->app->bind('MiBoMoney', static function (Container $container): MoneyManager {
            return new MoneyManager($container);
        });
    }

    /**
     * @inheritDoc
     *
     * @return string[]
     */
    public function provides(): array
    {
        return ['MiBoMoney'];
    }
}
