<?php

declare(strict_types=1);

namespace MiBo\Prices\Tests\Casting;

use Carbon\Carbon;
use Closure;
use Illuminate\Database\Eloquent\Model;
use MiBo\Prices\Data\Casting\PriceAttribute;
use MiBo\Prices\Exceptions\NegativePriceException;
use MiBo\Prices\Tests\LaravelTestCase;
use ValueError;
use function is_string;

/**
 * Class PriceStoringCastingTest
 *
 * @package MiBo\Prices\Tests\Casting
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 *
 * @coversDefaultClass \MiBo\Prices\Data\Casting\PriceAttribute
 */
class PriceStoringCastingTest extends LaravelTestCase
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
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testDefaultCast(Closure $createPrice): void
    {
        $model        = $this->createModel(PriceAttribute::class);
        $price        = $createPrice();
        $model->price = $price;

        $this->assertSame($price->getValue(), $model->price->getValue());
        $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());
        $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());

        $this->assertSame($price->getUnit()->getAlphabeticalCode(), $model->price_currency);
        $this->assertSame(
            $price->getVAT()->getCategory() ?: null,
            $model->price_category ?: null
        );
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price_country);
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
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testInMajorCast(Closure $createPrice): void
    {
        $model        = $this->createModel(PriceAttribute::class . ':inMinor-false');
        $price        = $createPrice();
        $model->price = $price;

        $this->assertSame($price->getValue(), $model->price->getValue());
        $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());
        $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());

        $this->assertSame($price->getUnit()->getAlphabeticalCode(), $model->price_currency);
        $this->assertSame(
            $price->getVAT()->getCategory() ?: null,
            $model->price_category ?: null
        );
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price_country);
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
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testMinorCast(Closure $createPrice): void
    {
        $model        = $this->createModel(PriceAttribute::class . ':inMinor-true');
        $price        = $createPrice();
        $model->price = $price;

        $this->assertSame($price->getValue(), $model->price->getValue());
        $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());
        $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());

        $this->assertSame($price->getUnit()->getAlphabeticalCode(), $model->price_currency);
        $this->assertSame(
            $price->getVAT()->getCategory() ?: null,
            $model->price_category ?: null
        );
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price_country);
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
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testCurrencyFixedCast(Closure $createPrice): void
    {
        $model        = $this->createModel(PriceAttribute::class . ':currency-CZK');
        $price        = $createPrice();
        $model->price = $price;

        $this->assertSame($price->getValue(), $model->price->getValue());
        $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertSame('CZK', $model->price->getUnit()->getAlphabeticalCode());
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());
        $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());
        $this->assertSame(
            $price->getVAT()->getCategory() ?: null,
            $model->price_category ?: null
        );
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price_country);
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
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testCurrencyNotProvidedCast(Closure $createPrice): void
    {
        $model        = $this->createModel(
            PriceAttribute::class,
            [
                'price',
                'price_category',
                'price_country',
                'price_date',
            ]
        );
        $price        = $createPrice();
        $model->price = $price;

        $this->assertSame($price->getValue(), $model->price->getValue());
        $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertSame('USD', $price->getUnit()->getAlphabeticalCode());
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());
        $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());
        $this->assertSame(
            $price->getVAT()->getCategory() ?: null,
            $model->price_category ?: null
        );
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price_country);
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
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testCurrencyDifferentColumnCast(Closure $createPrice): void
    {
        $model        = $this->createModel(
            PriceAttribute::class . ':currency-_ccr',
            [
                'price',
                'price_category',
                'price_ccr',
                'price_country',
                'price_date',
            ]
        );
        $price        = $createPrice();
        $model->price = $price;

        $this->assertSame($price->getValue(), $model->price->getValue());
        $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());
        $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());

        $this->assertSame($price->getUnit()->getAlphabeticalCode(), $model->price_ccr);
        $this->assertSame(
            $price->getVAT()->getCategory() ?: null,
            $model->price_category ?: null
        );
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price_country);
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
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testNegativesForbiddenCast(Closure $createPrice): void
    {
        $model = $this->createModel(PriceAttribute::class . ':positive-true');
        $price = $createPrice();

        if ($price->isNegative()) {
            $this->expectException(NegativePriceException::class);
        }

        $model->price = $price;

        $this->assertSame($price->getValue(), $model->price->getValue());
        $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());
        $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());

        $this->assertSame($price->getUnit()->getAlphabeticalCode(), $model->price_currency);
        $this->assertSame(
            $price->getVAT()->getCategory() ?: null,
            $model->price_category ?: null
        );
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price_country);
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
         * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceCastingProvider::getApplicationData()
         */
    public function testNegativePossibleCast(Closure $createPrice): void
        {
        $model        = $this->createModel(PriceAttribute::class . ':positive-false');
        $price        = $createPrice();
        $model->price = $price;

        $this->assertSame($price->getValue(), $model->price->getValue());
        $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());
        $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());

        $this->assertSame($price->getUnit()->getAlphabeticalCode(), $model->price_currency);
        $this->assertSame(
            $price->getVAT()->getCategory() ?: null,
            $model->price_category ?: null
        );
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price_country);
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
         * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceCastingProvider::getApplicationData()
         */
    public function testSpecifiedCategoryCast(Closure $createPrice): void
        {
        $model = $this->createModel(PriceAttribute::class . ':category-07');
        $price = $createPrice();

        if ($price->getVAT()->getCategory() !== '07') {
            $this->expectException(ValueError::class);
        }

        $model->price = $price;

        $this->assertSame($price->getValue(), $model->price->getValue());
        $this->assertSame('07', $model->price->getVAT()->getCategory());
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());
        $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());
        $this->assertSame($price->getUnit()->getAlphabeticalCode(), $model->price_currency);
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price_country);
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
         * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceCastingProvider::getApplicationData()
         */
    public function testCategoryDifferentColumnCast(Closure $createPrice): void
        {
        $model        = $this->createModel(
            PriceAttribute::class . ':category-_cat',
            [
                'price',
                'price_currency',
                'price_country',
                'price_cat',
                'price_date',
            ]
        );
        $price        = $createPrice();
        $model->price = $price;

        $this->assertSame($price->getValue(), $model->price->getValue());
        $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());
        $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());
        $this->assertSame($price->getUnit()->getAlphabeticalCode(), $model->price_currency);
        $this->assertSame(
            $price->getVAT()->getCategory() ?: null,
            $model->price_cat ?: null
        );
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price_country);
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
         * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceCastingProvider::getApplicationData()
         */
    public function testCategoryCallbackCast(Closure $createPrice): void
        {
        $model = $this->createModel(PriceAttribute::class . ':category-');
        $price = $createPrice();

        PriceAttribute::setCategoryCallback(
            function(bool $retrieve, Model $model, string $key, $value) {
                static $data = [];

                if ($retrieve === false) {
                    $data[$key] = $value->getVAT()->getCategory();
                }

                if ($retrieve === true) {
                    return $data[$key] ?? null;
                }

                return true;
            }
        );

        $model->price = $price;

        $this->assertSame($price->getValue(), $model->price->getValue());
        $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());
        $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());

        $this->assertSame($price->getUnit()->getAlphabeticalCode(), $model->price_currency);
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price_country);
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
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testCountryCast(Closure $createPrice): void
    {
        $model        = $this->createModel(PriceAttribute::class . ':country-SVK');
        $price        = $createPrice();
        $model->price = $price;

        $this->assertSame($price->getValue(), $model->price->getValue());
        $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
        $this->assertSame('SVK', $model->price->getVAT()->getCountryCode());
        $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());

        $this->assertSame($price->getUnit()->getAlphabeticalCode(), $model->price_currency);
        $this->assertSame(
            $price->getVAT()->getCategory() ?: null,
            $model->price_category ?: null
        );
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
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testCountryDifferentColumnCast(Closure $createPrice): void
    {
        $model        = $this->createModel(
            PriceAttribute::class . ':country-_cntry',
            [
                'price',
                'price_currency',
                'price_cntry',
                'price_category',
                'price_date',
            ]
        );
        $price        = $createPrice();
        $model->price = $price;

        $this->assertSame($price->getValue(), $model->price->getValue());
        $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());
        $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());

        $this->assertSame($price->getUnit()->getAlphabeticalCode(), $model->price_currency);
        $this->assertSame(
            $price->getVAT()->getCategory() ?: null,
            $model->price_category ?: null
        );
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price_cntry);
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
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceCastingProvider::getApplicationData()
     */
    public function testCountryNotProvidedCast(Closure $createPrice): void
    {
        $model        = $this->createModel(
            PriceAttribute::class,
            [
                'price',
                'price_currency',
                'price_category',
                'price_date',
            ]
        );
        $price        = $createPrice();
        $model->price = $price;

        $this->assertSame($price->getValue(), $model->price->getValue());
        $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());
        $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());

        $this->assertSame($price->getUnit()->getAlphabeticalCode(), $model->price_currency);
        $this->assertSame(
            $price->getVAT()->getCategory() ?: null,
            $model->price_category ?: null
        );
    }

    //     * @small
    //     *
    //     * @covers ::__construct
    //     * @covers ::set
    //     *
    //     * @param \Closure(): \MiBo\Prices\Price $createPrice
    //     *
    //     * @return void
    //     *
    //     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceCastingProvider::getApplicationData()
    //     */
    //    public function testDefaultCast(Closure $createPrice): void
    //    {
    //        $model        = $this->createModel(PriceAttribute::class);
    //        $price        = $createPrice();
    //        $model->price = $price;
    //
    //        $this->assertSame($price->getValue(), $model->price->getValue());
    //        $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
    //        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
    //        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
    //        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());
    //        $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());
    //
    //        $this->assertSame($price->getUnit()->getAlphabeticalCode(), $model->price_currency);
    //        $this->assertSame(
    //            $price->getVAT()->getCategory() ?: null,
    //            $model->price_category ?: null
    //        );
    //        $this->assertSame($price->getVAT()->getCountryCode(), $model->price_country);
    //    }

    //     * @small
    //     *
    //     * @covers ::__construct
    //     * @covers ::set
    //     *
    //     * @param \Closure(): \MiBo\Prices\Price $createPrice
    //     *
    //     * @return void
    //     *
    //     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceCastingProvider::getApplicationData()
    //     */
    //    public function testDefaultCast(Closure $createPrice): void
    //    {
    //        $model        = $this->createModel(PriceAttribute::class);
    //        $price        = $createPrice();
    //        $model->price = $price;
    //
    //        $this->assertSame($price->getValue(), $model->price->getValue());
    //        $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
    //        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
    //        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
    //        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());
    //        $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());
    //
    //        $this->assertSame($price->getUnit()->getAlphabeticalCode(), $model->price_currency);
    //        $this->assertSame(
    //            $price->getVAT()->getCategory() ?: null,
    //            $model->price_category ?: null
    //        );
    //        $this->assertSame($price->getVAT()->getCountryCode(), $model->price_country);
    //    }

    //     * @small
    //     *
    //     * @covers ::__construct
    //     * @covers ::set
    //     *
    //     * @param \Closure(): \MiBo\Prices\Price $createPrice
    //     *
    //     * @return void
    //     *
    //     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceCastingProvider::getApplicationData()
    //     */
    //    public function testDefaultCast(Closure $createPrice): void
    //    {
    //        $model        = $this->createModel(PriceAttribute::class);
    //        $price        = $createPrice();
    //        $model->price = $price;
    //
    //        $this->assertSame($price->getValue(), $model->price->getValue());
    //        $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
    //        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
    //        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
    //        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());
    //        $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());
    //
    //        $this->assertSame($price->getUnit()->getAlphabeticalCode(), $model->price_currency);
    //        $this->assertSame(
    //            $price->getVAT()->getCategory() ?: null,
    //            $model->price_category ?: null
    //        );
    //        $this->assertSame($price->getVAT()->getCountryCode(), $model->price_country);
    //    }

    //     * @small
    //     *
    //     * @covers ::__construct
    //     * @covers ::set
    //     *
    //     * @param \Closure(): \MiBo\Prices\Price $createPrice
    //     *
    //     * @return void
    //     *
    //     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceCastingProvider::getApplicationData()
    //     */
    //    public function testDefaultCast(Closure $createPrice): void
    //    {
    //        $model        = $this->createModel(PriceAttribute::class);
    //        $price        = $createPrice();
    //        $model->price = $price;
    //
    //        $this->assertSame($price->getValue(), $model->price->getValue());
    //        $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
    //        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
    //        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
    //        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());
    //        $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());
    //
    //        $this->assertSame($price->getUnit()->getAlphabeticalCode(), $model->price_currency);
    //        $this->assertSame(
    //            $price->getVAT()->getCategory() ?: null,
    //            $model->price_category ?: null
    //        );
    //        $this->assertSame($price->getVAT()->getCountryCode(), $model->price_country);
    //    }

    //     * @small
    //     *
    //     * @covers ::__construct
    //     * @covers ::set
    //     *
    //     * @param \Closure(): \MiBo\Prices\Price $createPrice
    //     *
    //     * @return void
    //     *
    //     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceCastingProvider::getApplicationData()
    //     */
    //    public function testDefaultCast(Closure $createPrice): void
    //    {
    //        $model        = $this->createModel(PriceAttribute::class);
    //        $price        = $createPrice();
    //        $model->price = $price;
    //
    //        $this->assertSame($price->getValue(), $model->price->getValue());
    //        $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
    //        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
    //        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
    //        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());
    //        $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());
    //
    //        $this->assertSame($price->getUnit()->getAlphabeticalCode(), $model->price_currency);
    //        $this->assertSame(
    //            $price->getVAT()->getCategory() ?: null,
    //            $model->price_category ?: null
    //        );
    //        $this->assertSame($price->getVAT()->getCountryCode(), $model->price_country);
    //    }

    //     * @small
    //     *
    //     * @covers ::__construct
    //     * @covers ::set
    //     *
    //     * @param \Closure(): \MiBo\Prices\Price $createPrice
    //     *
    //     * @return void
    //     *
    //     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceCastingProvider::getApplicationData()
    //     */
    //    public function testDefaultCast(Closure $createPrice): void
    //    {
    //        $model        = $this->createModel(PriceAttribute::class);
    //        $price        = $createPrice();
    //        $model->price = $price;
    //
    //        $this->assertSame($price->getValue(), $model->price->getValue());
    //        $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
    //        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
    //        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
    //        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());
    //        $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());
    //
    //        $this->assertSame($price->getUnit()->getAlphabeticalCode(), $model->price_currency);
    //        $this->assertSame(
    //            $price->getVAT()->getCategory() ?: null,
    //            $model->price_category ?: null
    //        );
    //        $this->assertSame($price->getVAT()->getCountryCode(), $model->price_country);
    //    }

    private function createModel(
        string $cast,
        array $fillable = [
            'price',
            'price_currency',
            'price_country',
            'price_category',
            'price_date',
        ],
        string $defaultProp = 'price'
    ): Model
    {
        return new class ([], $fillable, $cast, $defaultProp) extends Model
        {
            protected $guarded = [];

            public function __construct(array $attributes = [], $fillable = [], $cast = '', $defaultProp = '')
            {
                $this->fillable = $fillable;
                $this->casts    = [$defaultProp => $cast];
                $this->visible  = $fillable;

                parent::__construct($attributes);
            }
        };
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        PriceAttribute::setCategoryCallback(null);

        parent::tearDown();
    }
}
