<?php

declare(strict_types=1);

namespace MiBo\Properties\Contracts;

use MiBo\Prices\Contracts\PriceInterface;

/**
 * Interface HasPrice
 *
 *  This Interface is used to indicate that the object has a price, which directly values
 * the object. The Price of the object should be globally accessible through the getPrice()
 * method. The result of that method should be a final price of that object, that means
 * that if the price of the object decreases, the getPrice() method should return lower
 * price than that of the original price.
 *
 *  When working with an object that should have a price, prefer to use this interface.
 *
 * @package MiBo\Properties\Contracts
 *
 * @author 6I012
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
interface HasPrice
{
    /**
     * Get the price of the object.
     *
     * @return \MiBo\Prices\Contracts\PriceInterface Price of the object.
     */
    public function getPrice(): PriceInterface;
}
