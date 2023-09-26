<?php

declare(strict_types=1);

namespace MiBo\Properties\Tests\Coverage\Casting;

use MiBo\Properties\Tests\Casting\PriceRetrieveCastingTest as BaseTest;

/**
 * Class PriceRetrieveCastingTest
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
class PriceRetrieveCastingTest extends BaseTest
{
    /**
     * @small
     *
     * @covers \MiBo\Properties\Data\Factories\PriceFactory::get
     * @covers \MiBo\Properties\Data\Factories\PriceFactory::__construct
     * @covers ::__construct
     * @covers ::get
     *
     * @param array{
     *     id: positive-int,
     *     price: int|float,
     *     price_currency: string,
     *     price_country: string,
     *     price_date: \DateTimeInterface|string|null,
     *     price_category: string,
     *     currency: string,
     *     country: string,
     *     price_cntry: string,
     *     price_cat: string,
     * } $data
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testDefaultCaster(array $data): void
    {
        parent::testDefaultCaster($data);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param array{
     *     id: positive-int,
     *     price: int|float,
     *     price_currency: string,
     *     price_country: string,
     *     price_date: \DateTimeInterface|string|null,
     *     price_category: string,
     *     currency: string,
     *     country: string,
     *     price_cntry: string,
     *     price_cat: string,
     * } $data
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testInMajorCaster(array $data): void
    {
        parent::testInMajorCaster($data);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param array{
     *     id: positive-int,
     *     price: int|float,
     *     price_currency: string,
     *     price_country: string,
     *     price_date: \DateTimeInterface|string|null,
     *     price_category: string,
     *     currency: string,
     *     country: string,
     *     price_cntry: string,
     *     price_cat: string,
     * } $data
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testInMinorCaster(array $data): void
    {
        parent::testInMinorCaster($data);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param array{
     *     id: positive-int,
     *     price: int|float,
     *     price_currency: string,
     *     price_country: string,
     *     price_date: \DateTimeInterface|string|null,
     *     price_category: string,
     *     currency: string,
     *     country: string,
     *     price_cntry: string,
     *     price_cat: string,
     * } $data
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testCurrencyFixedCaster(array $data): void
    {
        parent::testCurrencyFixedCaster($data);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param array{
     *     id: positive-int,
     *     price: int|float,
     *     price_currency: string,
     *     price_country: string,
     *     price_date: \DateTimeInterface|string|null,
     *     price_category: string,
     *     currency: string,
     *     country: string,
     *     price_cntry: string,
     *     price_cat: string,
     * } $data
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testCurrencyNotProvidedCaster(array $data): void
    {
        parent::testCurrencyNotProvidedCaster($data);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param array{
     *     id: positive-int,
     *     price: int|float,
     *     price_currency: string,
     *     price_country: string,
     *     price_date: \DateTimeInterface|string|null,
     *     price_category: string,
     *     currency: string,
     *     country: string,
     *     price_cntry: string,
     *     price_cat: string,
     * } $data
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testCurrencyDifferentColumn1Caster(array $data): void
    {
        parent::testCurrencyDifferentColumn1Caster($data);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param array{
     *     id: positive-int,
     *     price: int|float,
     *     price_currency: string,
     *     price_country: string,
     *     price_date: \DateTimeInterface|string|null,
     *     price_category: string,
     *     currency: string,
     *     country: string,
     *     price_cntry: string,
     *     price_cat: string,
     * } $data
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testCurrencyDifferentColumn2Caster(array $data): void
    {
        parent::testCurrencyDifferentColumn2Caster($data);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param array{
     *     id: positive-int,
     *     price: int|float,
     *     price_currency: string,
     *     price_country: string,
     *     price_date: \DateTimeInterface|string|null,
     *     price_category: string,
     *     currency: string,
     *     country: string,
     *     price_cntry: string,
     *     price_cat: string,
     * } $data
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testNegativesPossibleCaster(array $data): void
    {
        parent::testNegativesPossibleCaster($data);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param array{
     *     id: positive-int,
     *     price: int|float,
     *     price_currency: string,
     *     price_country: string,
     *     price_date: \DateTimeInterface|string|null,
     *     price_category: string,
     *     currency: string,
     *     country: string,
     *     price_cntry: string,
     *     price_cat: string,
     * } $data
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testNegativesForbiddenCaster(array $data): void
    {
        parent::testNegativesForbiddenCaster($data);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param array{
     *     id: positive-int,
     *     price: int|float,
     *     price_currency: string,
     *     price_country: string,
     *     price_date: \DateTimeInterface|string|null,
     *     price_category: string,
     *     currency: string,
     *     country: string,
     *     price_cntry: string,
     *     price_cat: string,
     * } $data
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testSpecifiedCategoryCaster(array $data): void
    {
        parent::testSpecifiedCategoryCaster($data);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param array{
     *     id: positive-int,
     *     price: int|float,
     *     price_currency: string,
     *     price_country: string,
     *     price_date: \DateTimeInterface|string|null,
     *     price_category: string,
     *     currency: string,
     *     country: string,
     *     price_cntry: string,
     *     price_cat: string,
     * } $data
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testCategoryDifferentColumnCaster(array $data): void
    {
        parent::testCategoryDifferentColumnCaster($data);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param array{
     *     id: positive-int,
     *     price: int|float,
     *     price_currency: string,
     *     price_country: string,
     *     price_date: \DateTimeInterface|string|null,
     *     price_category: string,
     *     currency: string,
     *     country: string,
     *     price_cntry: string,
     *     price_cat: string,
     * } $data
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testCategoryEmptyCaster(array $data): void
    {
        parent::testCategoryEmptyCaster($data);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     * @covers ::setCategoryCallback
     *
     * @param array{
     *     id: positive-int,
     *     price: int|float,
     *     price_currency: string,
     *     price_country: string,
     *     price_date: \DateTimeInterface|string|null,
     *     price_category: string,
     *     currency: string,
     *     country: string,
     *     price_cntry: string,
     *     price_cat: string,
     * } $data
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testCategoryCallbackCaster(array $data): void
    {
        parent::testCategoryCallbackCaster($data);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param array{
     *     id: positive-int,
     *     price: int|float,
     *     price_currency: string,
     *     price_country: string,
     *     price_date: \DateTimeInterface|string|null,
     *     price_category: string,
     *     currency: string,
     *     country: string,
     *     price_cntry: string,
     *     price_cat: string,
     * } $data
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testCountryCaster(array $data): void
    {
        parent::testCountryCaster($data);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param array{
     *     id: positive-int,
     *     price: int|float,
     *     price_currency: string,
     *     price_country: string,
     *     price_date: \DateTimeInterface|string|null,
     *     price_category: string,
     *     currency: string,
     *     country: string,
     *     price_cntry: string,
     *     price_cat: string,
     * } $data
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testCountryDifferentColumnCaster(array $data): void
    {
        parent::testCountryDifferentColumnCaster($data);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param array{
     *     id: positive-int,
     *     price: int|float,
     *     price_currency: string,
     *     price_country: string,
     *     price_date: \DateTimeInterface|string|null,
     *     price_category: string,
     *     currency: string,
     *     country: string,
     *     price_cntry: string,
     *     price_cat: string,
     * } $data
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testCountryNotProvidedCaster(array $data): void
    {
        parent::testCountryNotProvidedCaster($data);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param array{
     *     id: positive-int,
     *     price: int|float,
     *     price_currency: string,
     *     price_country: string,
     *     price_date: \DateTimeInterface|string|null,
     *     price_category: string,
     *     currency: string,
     *     country: string,
     *     price_cntry: string,
     *     price_cat: string,
     * } $data
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testDateCaster(array $data): void
    {
        parent::testDateCaster($data);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param array{
     *     id: positive-int,
     *     price: int|float,
     *     price_currency: string,
     *     price_country: string,
     *     price_date: \DateTimeInterface|string|null,
     *     price_category: string,
     *     currency: string,
     *     country: string,
     *     price_cntry: string,
     *     price_cat: string,
     * } $data
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testDateColumnCaster(array $data): void
    {
        parent::testDateColumnCaster($data);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param array{
     *     id: positive-int,
     *     price: int|float,
     *     price_currency: string,
     *     price_country: string,
     *     price_date: \DateTimeInterface|string|null,
     *     price_category: string,
     *     currency: string,
     *     country: string,
     *     price_cntry: string,
     *     price_cat: string,
     * } $data
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testDateColumnForeignCaster(array $data): void
    {
        parent::testDateColumnForeignCaster($data);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param array{
     *     id: positive-int,
     *     price: int|float,
     *     price_currency: string,
     *     price_country: string,
     *     price_date: \DateTimeInterface|string|null,
     *     price_category: string,
     *     currency: string,
     *     country: string,
     *     price_cntry: string,
     *     price_cat: string,
     * } $data
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testDateNotProvidedCaster(array $data): void
    {
        parent::testDateNotProvidedCaster($data);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param array{
     *     id: positive-int,
     *     price: int|float,
     *     price_currency: string,
     *     price_country: string,
     *     price_date: \DateTimeInterface|string|null,
     *     price_category: string,
     *     currency: string,
     *     country: string,
     *     price_cntry: string,
     *     price_cat: string,
     * } $data
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testAnyVATTrueCaster(array $data): void
    {
        parent::testAnyVATTrueCaster($data);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param array{
     *     id: positive-int,
     *     price: int|float,
     *     price_currency: string,
     *     price_country: string,
     *     price_date: \DateTimeInterface|string|null,
     *     price_category: string,
     *     currency: string,
     *     country: string,
     *     price_cntry: string,
     *     price_cat: string,
     * } $data
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testAnyVATFalseCaster(array $data): void
    {
        parent::testAnyVATFalseCaster($data);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param array{
     *     id: positive-int,
     *     price: int|float,
     *     price_currency: string,
     *     price_country: string,
     *     price_date: \DateTimeInterface|string|null,
     *     price_category: string,
     *     currency: string,
     *     country: string,
     *     price_cntry: string,
     *     price_cat: string,
     * } $data
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testWithVATTrueCaster(array $data): void
    {
        parent::testWithVATTrueCaster($data);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param array{
     *     id: positive-int,
     *     price: int|float,
     *     price_currency: string,
     *     price_country: string,
     *     price_date: \DateTimeInterface|string|null,
     *     price_category: string,
     *     currency: string,
     *     country: string,
     *     price_cntry: string,
     *     price_cat: string,
     * } $data
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testWithVATFalseCaster(array $data): void
    {
        parent::testWithVATFalseCaster($data);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param array{
     *     id: positive-int,
     *     price: int|float,
     *     price_currency: string,
     *     price_country: string,
     *     price_date: \DateTimeInterface|string|null,
     *     price_category: string,
     *     currency: string,
     *     country: string,
     *     price_cntry: string,
     *     price_cat: string,
     * } $data
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testCombinationCaster(array $data): void
    {
        parent::testCombinationCaster($data);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param array{
     *     id: positive-int,
     *     price: int|float,
     *     price_currency: string,
     *     price_country: string,
     *     price_date: \DateTimeInterface|string|null,
     *     price_category: string,
     *     currency: string,
     *     country: string,
     *     price_cntry: string,
     *     price_cat: string,
     * } $data
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testUselessCastingCaster(array $data): void
    {
        parent::testUselessCastingCaster($data);
    }
}
