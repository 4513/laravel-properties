<?php

declare(strict_types=1);

namespace MiBo\Properties\Contracts;

/**
 * Interface SubjectToTax
 *
 * @package MiBo\Properties\Contracts
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
interface SubjectToTax
{
    /**
     * Determines whether the subject pays Value Added Tax.
     *
     *  The method might be used to decide whether a price should be displayed
     * with or without VAT, if a created order should contain its final price
     * to pay with or without VAT etc.
     *
     * @return bool True if the subject pays VAT, false otherwise.
     */
    public function paysVAT(): bool;
}
