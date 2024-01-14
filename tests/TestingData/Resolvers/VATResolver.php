<?php

declare(strict_types=1);

namespace MiBo\Properties\Tests\TestingData\Resolvers;

use Carbon\Carbon;
use DateTimeInterface;
use MiBo\Taxonomy\Contracts\ProductTaxonomy;
use MiBo\VAT\Contracts\Convertor;
use MiBo\VAT\Contracts\ValueResolver;
use MiBo\VAT\Enums\VATRate;
use MiBo\VAT\VAT;
use Stringable;

/**
 * Class VATResolver
 *
 * @package MiBo\Properties\Tests\TestingData\Resolvers
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
class VATResolver implements \MiBo\VAT\Contracts\VATResolver, Convertor, ValueResolver
{
    /**
     * @return array<string, array<string, \MiBo\VAT\Enums\VATRate>>
     */
    private static function getVATs(): array
    {
        return [
            "CZE" => [
                "9705 00 00" => VATRate::REDUCED,
                "9704 00 00" => VATRate::REDUCED,
                "2201"       => VATRate::SECOND_REDUCED,
                "06"         => VATRate::NONE,
                "07"         => VATRate::NONE,
                "08"         => VATRate::NONE,
                "09"         => VATRate::NONE,
                "10"         => VATRate::NONE,
                "1"          => VATRate::STANDARD,
                "2"          => VATRate::STANDARD,
            ],
            "SVK" => [
                "07" => VATRate::REDUCED,
                "08" => VATRate::REDUCED, // 36.75
                "1"  => VATRate::STANDARD,
                "2"  => VATRate::STANDARD,
            ],
            'US'  => [
                '07' => VATRate::STANDARD,
                '08' => VATRate::STANDARD,
                '06' => VATRate::STANDARD,
                '02' => VATRate::STANDARD,
                '1'  => VATRate::STANDARD,
            ],
        ];
    }

    /**
     * @return array<string, array<value-of<\MiBo\VAT\Enums\VATRate>, float>>
     */
    private static function getPercentages(): array
    {
        return [
            "CZE" => [
                VATRate::STANDARD->name       => 0.21,
                VATRate::SECOND_REDUCED->name => 0.10,
                VATRate::REDUCED->name        => 0.15,
                VATRate::NONE->name           => 0,
            ],
            "SVK" => [
                VATRate::STANDARD->name       => 0.20,
                VATRate::REDUCED->name        => 0.10,
                VATRate::NONE->name           => 0,
                VATRate::SECOND_REDUCED->name => 0.20,
            ],
            'US'  => [
                VATRate::STANDARD->name       => 0.20,
                VATRate::REDUCED->name        => 0.20,
                VATRate::NONE->name           => 0,
                VATRate::SECOND_REDUCED->name => 0.20,
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function convert(VAT $vat, ?string $countryCode = null, ?DateTimeInterface $date = null): VAT
    {
        return $this->retrieveVAT(
            $vat->getClassification(),
            $countryCode ?? $vat->getCountryCode(),
            $date ?? $vat->getDate()
        );
    }

    /**
     * @inheritDoc
     */
    public function retrieveVAT(
        ProductTaxonomy $classification,
        Stringable|string $countryCode,
        ?DateTimeInterface $date
    ): VAT
    {
        if (empty(self::getVATs()[(string) $countryCode][$classification->getCode()])) {
            return VAT::get($countryCode, VATRate::STANDARD, $classification, $date ?? Carbon::now());
        }

        return VAT::get(
            $countryCode,
            self::getVATs()[(string) $countryCode][$classification->getCode()],
            $classification,
            $date ?? Carbon::now()
        );
    }

    /**
     * @inheritDoc
     */
    public function getValueOfVAT(VAT $vat): float|int
    {
        if ($vat->isAny() || $vat->isNone() || $vat->isCombined()) {
            return 0;
        }

        if ($vat->getCountryCode() === '') {
            return 0;
        }

        return self::getPercentages()[$vat->getCountryCode()][$vat->getRate()->name];
    }
}
