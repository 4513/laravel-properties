<?php

declare(strict_types=1);

namespace MiBo\Properties\Contracts;

use MiBo\Prices\Contracts\PriceInterface;

/**
 * Interface Discountable
 *
 *  This Interface represents a class that can be discounted, meaning any class that has
 * a price and a discount can be applied to it.
 *
 *  When trying to apply a discount to this object, the discounter or an implementation
 * of a class that applies the discount calls the registerDiscountPrice() method, which
 * registers the discount on this object.
 *
 * @package MiBo\Properties\Contracts
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
interface Discountable extends HasPrice
{
    /**
     * Register a discount price.
     *
     *  When registering a discount on this object, the discounter or any implementation
     * of the class responsible for creating and applying discount should call this method.
     *
     * @param \MiBo\Prices\Contracts\PriceInterface $discount Discount price.
     *
     * @return void
     */
    public function registerDiscountPrice(PriceInterface $discount): void;

    /**
     * Get the applied discount price on this object.
     *
     * @return \MiBo\Prices\Contracts\PriceInterface Discount price.
     */
    public function getDiscountedPrice(): PriceInterface;
}
