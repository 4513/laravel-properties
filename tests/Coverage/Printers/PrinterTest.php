<?php

declare(strict_types=1);

namespace MiBo\Properties\Tests\Coverage\Printers;

use MiBo\Properties\Tests\Printers\PrinterTest as BaseTest;
use MiBo\Properties\Contracts\NumericalProperty;

/**
 * Class PrinterTest
 *
 * @package MiBo\Properties\Tests\Coverage\Printers
 *
 * @author 9I214
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 *
 * @coversDefaultClass \MiBo\Properties\Printers\Printer
 */
class PrinterTest extends BaseTest
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
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PrinterProvider::getDataToFormat()
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
