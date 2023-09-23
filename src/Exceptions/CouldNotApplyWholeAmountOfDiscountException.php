<?php

declare(strict_types=1);

namespace MiBo\Prices\Exceptions;

use RuntimeException;

/**
 * Class CouldNotApplyWholeAmountOfDiscountException
 *
 * @package MiBo\Prices\Exceptions
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
class CouldNotApplyWholeAmountOfDiscountException extends RuntimeException implements DiscountException
{
    /**
     * @param int|float $left
     */
    public function __construct(int|float $left)
    {
        parent::__construct("Failed to apply whole amount of the discount. Left: {$left}");
    }
}
