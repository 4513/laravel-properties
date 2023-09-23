<?php

declare(strict_types=1);

namespace MiBo\Prices\Contracts;

/**
 * Interface Discountable
 *
 * @package MiBo\Prices\Contracts
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
interface Discountable extends HasPrice
{
    public function registerDiscountPrice(PriceInterface $discount): void;
}
