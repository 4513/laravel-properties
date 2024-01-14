<?php

declare(strict_types=1);

namespace MiBo\Properties\Classifications;

use MiBo\Taxonomy\Contracts\ProductTaxonomy;

/**
 * Class Creator
 *
 * @package MiBo\Properties\Classifications
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
class Creator
{
    private array $classifications = [];

    public function createFromString(string $classification): ProductTaxonomy
    {
        if (key_exists($classification, $this->classifications)) {
            return $this->classifications[$classification];
        }

        $classification = new class ($classification) implements ProductTaxonomy {
            public function __construct(
                private readonly string $classification
            )
            {
            }

            public function getCode(): string
            {
                return $this->classification;
            }

            public function is(string|ProductTaxonomy $code): bool
            {
                return $this->classification === ($code instanceof ProductTaxonomy ? $code->getCode() : $code);
            }

            public function belongsTo(string|ProductTaxonomy $code): bool
            {
                return false;
            }

            public function wraps(string|ProductTaxonomy $code): bool
            {
                return false;
            }

            public static function isValid(string $code): bool
            {
                return true;
            }
        };

        $this->classifications[$classification->getCode()] = $classification;

        return $classification;
    }
}
