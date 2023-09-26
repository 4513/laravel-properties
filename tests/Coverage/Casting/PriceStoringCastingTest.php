<?php

declare(strict_types=1);

namespace MiBo\Properties\Tests\Coverage\Casting;

use Closure;
use MiBo\Properties\Tests\Casting\PriceStoringCastingTest as BaseTest;

/**
 * Class PriceStoringCastingTest
 *
 * @package MiBo\Properties\Tests\Coverage\Casting
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 *
 * @coversDefaultClass \MiBo\Properties\Data\Casting\PriceAttribute
 */
class PriceStoringCastingTest extends BaseTest
{
    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::set
     *
     * @param \Closure(): \MiBo\Prices\Price $createPrice
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testDefaultCast(Closure $createPrice): void
    {
        parent::testDefaultCast($createPrice);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::set
     *
     * @param \Closure(): \MiBo\Prices\Price $createPrice
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testInMajorCast(Closure $createPrice): void
    {
        parent::testInMajorCast($createPrice);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::set
     *
     * @param \Closure(): \MiBo\Prices\Price $createPrice
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testMinorCast(Closure $createPrice): void
    {
        parent::testMinorCast($createPrice);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::set
     *
     * @param \Closure(): \MiBo\Prices\Price $createPrice
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testCurrencyFixedCast(Closure $createPrice): void
    {
        parent::testCurrencyFixedCast($createPrice);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::set
     *
     * @param \Closure(): \MiBo\Prices\Price $createPrice
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testCurrencyNotProvidedCast(Closure $createPrice): void
    {
        parent::testCurrencyNotProvidedCast($createPrice);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::set
     *
     * @param \Closure(): \MiBo\Prices\Price $createPrice
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testCurrencyDifferentColumnCast(Closure $createPrice): void
    {
        parent::testCurrencyDifferentColumnCast($createPrice);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::set
     *
     * @param \Closure(): \MiBo\Prices\Price $createPrice
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testNegativesForbiddenCast(Closure $createPrice): void
    {
        parent::testNegativesForbiddenCast($createPrice);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::set
     *
     * @param \Closure(): \MiBo\Prices\Price $createPrice
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testNegativePossibleCast(Closure $createPrice): void
    {
        parent::testNegativePossibleCast($createPrice);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::set
     *
     * @param \Closure(): \MiBo\Prices\Price $createPrice
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testSpecifiedCategoryCast(Closure $createPrice): void
    {
        parent::testSpecifiedCategoryCast($createPrice);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::set
     *
     * @param \Closure(): \MiBo\Prices\Price $createPrice
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testCategoryDifferentColumnCast(Closure $createPrice): void
    {
        parent::testCategoryDifferentColumnCast($createPrice);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::set
     *
     * @param \Closure(): \MiBo\Prices\Price $createPrice
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testCategoryCallbackCast(Closure $createPrice): void
    {
        parent::testCategoryCallbackCast($createPrice);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::set
     *
     * @param \Closure(): \MiBo\Prices\Price $createPrice
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testCountryCast(Closure $createPrice): void
    {
        parent::testCountryCast($createPrice);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::set
     *
     * @param \Closure(): \MiBo\Prices\Price $createPrice
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testCountryDifferentColumnCast(Closure $createPrice): void
    {
        parent::testCountryDifferentColumnCast($createPrice);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::set
     *
     * @param \Closure(): \MiBo\Prices\Price $createPrice
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testCountryNotProvidedCast(Closure $createPrice): void
    {
        parent::testCountryNotProvidedCast($createPrice);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::set
     *
     * @param \Closure(): \MiBo\Prices\Price $createPrice
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testDateCast(Closure $createPrice): void
    {
        parent::testDateCast($createPrice);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::set
     *
     * @param \Closure(): \MiBo\Prices\Price $createPrice
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testDateForeignColumnCast(Closure $createPrice): void
    {
        parent::testDateForeignColumnCast($createPrice);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::set
     *
     * @param \Closure(): \MiBo\Prices\Price $createPrice
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testDateNotProvidedCast(Closure $createPrice): void
    {
        parent::testDateNotProvidedCast($createPrice);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::set
     *
     * @param \Closure(): \MiBo\Prices\Price $createPrice
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testAnyVATTrueCast(Closure $createPrice): void
    {
        parent::testAnyVATTrueCast($createPrice);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::set
     *
     * @param \Closure(): \MiBo\Prices\Price $createPrice
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testAnyVATFalseCast(Closure $createPrice): void
    {
        parent::testAnyVATFalseCast($createPrice);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::set
     *
     * @param \Closure(): \MiBo\Prices\Price $createPrice
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testWithVATTrueCast(Closure $createPrice): void
    {
        parent::testWithVATTrueCast($createPrice);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::set
     *
     * @param \Closure(): \MiBo\Prices\Price $createPrice
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testWithVATFalseCast(Closure $createPrice): void
    {
        parent::testWithVATFalseCast($createPrice);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::set
     *
     * @param \Closure(): \MiBo\Prices\Price $createPrice
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testCombinationCast(Closure $createPrice): void
    {
        parent::testCombinationCast($createPrice);
    }
}
