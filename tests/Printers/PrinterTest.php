<?php

declare(strict_types=1);

namespace MiBo\Prices\Tests\Printers;

use MiBo\Currencies\CurrencyInterface;
use MiBo\Prices\Printers\Printer;
use MiBo\Prices\Tests\LaravelTestCase;
use MiBo\Properties\Contracts\NumericalProperty;

/**
 * Class PrinterTest
 *
 * @package MiBo\Prices\Tests\Printers
 *
 * @author 9I214
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
class PrinterTest extends LaravelTestCase
{
    /**
     * @small
     *
     * @coversNothing
     *
     * @param string $expectedResult
     * @param string $locale
     * @param \MiBo\Properties\Contracts\NumericalProperty $property
     * @param int|null $decimals
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PrinterProvider::getDataToFormat()
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PrinterProvider::getPricesToFormat()
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PrinterProvider::getDataWithNullableDecimals()
     */
    public function testPrintingProperty(
        string $expectedResult,
        string $locale,
        NumericalProperty $property,
        ?int $decimals = null
    ): void
    {
        app()->setLocale($locale);

        $printer = new Printer();

        $this->assertSame($expectedResult, $printer->printProperty($property, $decimals));
    }

    /**
     * @small
     *
     * @coversNothing
     *
     * @param string $expectedResult
     * @param string $locale
     * @param \MiBo\Properties\Contracts\NumericalProperty $property
     * @param int|null $decimal
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PrinterProvider::getDataToFormat()
     */
    public function testPrintingString(
        string $expectedResult,
        string $locale,
        NumericalProperty $property,
        ?int $decimal = null
    )
    {
        app()->setLocale($locale);

        $decimal ??= 0;
        $printer   = new Printer();
        $symbol    = $property->getUnit() instanceof CurrencyInterface ? '$' : $property->getUnit()->getSymbol();

        $this->assertSame(
            $expectedResult,
            $printer->print((string) $property->getValue(), $symbol, $decimal)
        );
    }
}
