<?php

declare(strict_types=1);

namespace MiBo\Prices\Providers;

use Illuminate\Support\ServiceProvider;

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
class FacadeProvider extends ServiceProvider
{
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
