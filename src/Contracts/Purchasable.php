<?php

declare(strict_types=1);

namespace MiBo\Prices\Contracts;

/**
 * Interface Purchasable
 *
 * @package MiBo\Prices\Contracts
 *
 * @author 6I012
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
interface Purchasable
{
    public function getPrice(): PriceInterface;
}
