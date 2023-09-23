<?php

declare(strict_types=1);

namespace MiBo\Prices\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Money
 *
 * @package MiBo\Prices\Facades
 *
 * @author 6I012
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 *
 * @mixin \MiBo\Prices\Managers\MoneyManager
 */
class Money extends Facade
{
    /**
     * @inheritDoc
     */
    protected static function getFacadeAccessor(): string
    {
        return 'MiBoMoney';
    }
}
