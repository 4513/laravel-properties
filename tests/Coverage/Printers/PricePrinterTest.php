<?php

declare(strict_types=1);

namespace MiBo\Properties\Tests\Coverage\Printers;

use MiBo\Properties\Tests\Printers\PricePrinterTest as BaseTest;
use MiBo\Properties\Contracts\NumericalProperty;

/**
 * Class PricePrinterTest
 *
 * @package MiBo\Properties\Tests\Coverage\Printers
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 *
 * @coversDefaultClass \MiBo\Properties\Printers\PricePrinter
 */
class PricePrinterTest extends BaseTest
{
    /**
     * @small
     *
     * @covers ::printProperty
     * @covers ::getTransKey
     * @covers ::getNumberFormat
     * @covers \MiBo\Properties\Providers\TranslationProvider::publishLanguages
     * @covers \MiBo\Properties\Providers\TranslationProvider::boot
     *
     * @param string $expectedResult
     * @param string $locale
     * @param \MiBo\Properties\Contracts\NumericalProperty $property
     * @param int|null $decimals
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PrinterProvider::getDataToFormat()
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PrinterProvider::getPricesToFormat()
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PrinterProvider::getDataWithNullableDecimals()
     */
    public function testPrintingProperty(
        string $expectedResult,
        string $locale,
        NumericalProperty $property,
        ?int $decimals = null
    ): void
    {
        parent::testPrintingProperty($expectedResult, $locale, $property, $decimals);
    }

    /**
     * @small
     *
     * @covers ::print
     * @covers ::getNumberFormat
     *
     * @param string $expectedResult
     * @param string $locale
     * @param \MiBo\Properties\Contracts\NumericalProperty $property
     * @param int|null $decimal
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PrinterProvider::getPricesToSimpleFormat()
     */
    public function testPrintingString(
        string $expectedResult,
        string $locale,
        NumericalProperty $property,
        ?int $decimal = null
    )
    {
        parent::testPrintingString($expectedResult, $locale, $property, $decimal);
    }
}
