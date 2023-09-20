<?php

declare(strict_types=1);

namespace MiBo\Prices\Tests\Casting;

use Carbon\Carbon;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use MiBo\Prices\Data\Casting\PriceAttribute;
use MiBo\Prices\Exceptions\NegativePriceException;
use MiBo\Prices\Tests\LaravelTestCase;
use MiBo\Prices\Units\Price\Currency;
use ValueError;
use function _PHPStan_95cdbe577\React\Promise\all;
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
 *
 * @phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
 */
class PriceStoringCastingTest extends LaravelTestCase
{
    use RefreshDatabase;

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

        $model->save();
        $model->refresh();

        if ($price->getVAT()->isNotAny()) {
            $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
            $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());
        }

        $this->assertEquals($price->getValue(), $model->price->getValue());
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());

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

        $model->save();
        $model->refresh();

        if ($price->getVAT()->isNotAny()) {
            $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
            $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());
        }

        $this->assertEquals($price->getValue(), $model->price->getValue());
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());

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

        $model->save();
        $model->refresh();

        if ($price->getVAT()->isNotAny()) {
            $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
            $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());
        }

        $this->assertEquals($price->getValue(), $model->price->getValue());
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());

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

        $model->save();
        $model->refresh();

        $price->convertToUnit(Currency::get('CZK'));

        if ($price->getVAT()->isNotAny()) {
            $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
            $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());
        }

        $this->assertEquals($price->getValue(), $model->price->getValue());
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());
        $this->assertSame('CZK', $model->price->getUnit()->getAlphabeticalCode());
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
            PriceAttribute::class . ':currency-',
            [
                'price',
                'price_category',
                'price_country',
                'price_date',
            ]
        );
        $price        = $createPrice();
        $model->price = $price;

        $model->save();
        $model->refresh();

        $price->convertToUnit($model->price->getUnit());

        if ($price->getVAT()->isNotAny()) {
            $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
            $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());
        }

        $this->assertEquals($price->getValue(), $model->price->getValue());
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertSame('USD', $model->price->getUnit()->getAlphabeticalCode());
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());

        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
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

        $model->save();
        $model->refresh();

        if ($price->getVAT()->isNotAny()) {
            $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
            $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());
        }

        $this->assertEquals($price->getValue(), $model->price->getValue());
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertSame($price->getUnit()->getAlphabeticalCode(), $model->price_ccr);
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());

        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
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

        $model->save();
        $model->refresh();

        if ($price->getVAT()->isNotAny()) {
            $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
            $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());
        }

        $this->assertEquals($price->getValue(), $model->price->getValue());
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertSame($price->getUnit()->getAlphabeticalCode(), $model->price->getUnit()->getAlphabeticalCode());
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());

        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
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

            $model->save();
            $model->refresh();

        if ($price->getVAT()->isNotAny()) {
            $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
            $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());
        }

            $this->assertEquals($price->getValue(), $model->price->getValue());
            $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
            $this->assertSame(
                $price->getUnit()->getAlphabeticalCode(),
                $model->price->getUnit()->getAlphabeticalCode()
            );
            $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());

            $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
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

        $model->save();
        $model->refresh();

        if ($price->getVAT()->isNotAny()) {
            $this->assertSame('07', $model->price->getVAT()->getCategory());
        }

        $this->assertEquals($price->getValue(), $model->price->getValue());
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertSame($price->getUnit()->getAlphabeticalCode(), $model->price->getUnit()->getAlphabeticalCode());
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());

        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
        $this->assertSame('07', $price->getVAT()->getCategory());
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

            $model->save();
            $model->refresh();

        if ($price->getVAT()->isNotAny()) {
            $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
            $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());
        }

            $this->assertEquals($price->getValue(), $model->price->getValue());
            $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
            $this->assertSame(
                $price->getUnit()->getAlphabeticalCode(),
                $model->price->getUnit()->getAlphabeticalCode()
            );
            $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());

            $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
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
            function(bool $retrieve, Model $model, array $attributes, string $key, $value) {
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

            $model->save();
            $model->refresh();

        if ($price->getVAT()->isNotAny()) {
            $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
            $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());
        }

            $this->assertEquals($price->getValue(), $model->price->getValue());
            $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
            $this->assertSame(
                $price->getUnit()->getAlphabeticalCode(),
                $model->price->getUnit()->getAlphabeticalCode()
            );
            $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());

            $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
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

        $model->save();
        $model->refresh();

        if ($price->getVAT()->isNotAny()) {
            $this->assertSame(
                $price->getVAT()->getCategory() ?: null,
                $model->price->getVAT()->getCategory() ?: null
            );
        }

        $this->assertEquals($price->getValue(), $model->price->getValue());
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
        $this->assertSame('SVK', $model->price->getVAT()->getCountryCode());

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

        $model->save();
        $model->refresh();

        if ($price->getVAT()->isNotAny()) {
            $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
            $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());
        }

        $this->assertEquals($price->getValue(), $model->price->getValue());
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());

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

        $model->save();
        $model->refresh();

        if ($price->getVAT()->isNotAny()) {
            $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
            $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());
        }

        $this->assertEquals($price->getValue(), $model->price->getValue());
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
        $this->assertSame('US', $model->price->getVAT()->getCountryCode());

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
    public function testDateCast(Closure $createPrice): void
    {
        $model        = $this->createModel(PriceAttribute::class . ':date-2019-01-01');
        $price        = $createPrice();
        $model->price = $price;

        $model->save();
        $model->refresh();

        if ($price->getVAT()->isNotAny()) {
            $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
            $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());
        }

        $this->assertEquals($price->getValue(), $model->price->getValue());
        $this->assertSame('2019-01-01', $model->price->getDateTime()->format('Y-m-d'));
        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());

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
    public function testDateForeignColumnCast(Closure $createPrice): void
    {
        $model        = $this->createModel(
            PriceAttribute::class . ':date-foreign_column',
            [
                'price',
                'price_currency',
                'price_country',
                'price_category',
                'foreign_column',
            ]
        );
        $price        = $createPrice();
        $model->price = $price;

        $model->save();
        $model->refresh();

        if ($price->getVAT()->isNotAny()) {
            $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
            $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());
        }

        $this->assertEquals($price->getValue(), $model->price->getValue());
        $this->assertSame(
            preg_replace('/\s[\d\.\:\-]+/', '', $model->foreign_column),
            $model->price->getDateTime()->format('Y-m-d')
        );
        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());

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
    public function testDateNotProvidedCast(Closure $createPrice): void
    {
        $model        = $this->createModel(
            PriceAttribute::class,
            [
                'price',
                'price_currency',
                'price_country',
                'price_category',
            ]
        );
        $price        = $createPrice();
        $model->price = $price;

        $model->save();
        $model->refresh();

        if ($price->getVAT()->isNotAny()) {
            $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
            $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());
        }

        $this->assertEquals($price->getValue(), $model->price->getValue());
        $this->assertSame(Carbon::now()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());

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
    public function testAnyVATTrueCast(Closure $createPrice): void
    {
        $model        = $this->createModel(PriceAttribute::class . ':any-true');
        $price        = $createPrice();
        $model->price = $price;

        $model->save();
        $model->refresh();

        $this->assertTrue($model->price->getVAT()->isAny());
        $this->assertEquals($price->getValue(), $model->price->getValue());
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());

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
    public function testAnyVATFalseCast(Closure $createPrice): void
    {
        $model        = $this->createModel(PriceAttribute::class . ':any-false');
        $price        = $createPrice();
        $model->price = $price;

        $model->save();
        $model->refresh();

        if ($price->getVAT()->isNotAny()) {
            $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
            $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());
        }

        $this->assertEquals($price->getValue(), $model->price->getValue());
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());

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
    public function testWithVATTrueCast(Closure $createPrice): void
    {
        $model        = $this->createModel(PriceAttribute::class . ':vat-true');
        $price        = $createPrice();
        $model->price = $price;

        $model->save();
        $model->refresh();

        if ($price->getVAT()->isNotAny()) {
            $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
            $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());
        }

        $this->assertEquals($price->getValueWithVAT(), $model->price->getValueWithVAT());
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());

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
    public function testWithVATFalseCast(Closure $createPrice): void
    {
        $model        = $this->createModel(PriceAttribute::class . ':vat-false');
        $price        = $createPrice();
        $model->price = $price;

        $model->save();
        $model->refresh();

        if ($price->getVAT()->isNotAny()) {
            $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
            $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());
        }

        $this->assertEquals($price->getValue(), $model->price->getValue());
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertTrue($price->getUnit()->is($model->price->getUnit()));
        $this->assertSame($price->getVAT()->getCountryCode(), $model->price->getVAT()->getCountryCode());

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
    public function testCombinationCast(Closure $createPrice): void
    {
        $model        = $this->createModel(PriceAttribute::class . ':currency-EUR,country-CZE');
        $price        = $createPrice();
        $model->price = $price;

        $model->save();
        $model->refresh();

        $price->forCountry('CZE');
        $price->convertToUnit(Currency::get('EUR'));

        if ($price->getVAT()->isNotAny()) {
            $this->assertTrue($price->getVAT()->is($model->price->getVAT()));
            $this->assertSame($price->getVAT()->getCategory(), $model->price->getVAT()->getCategory());
        }

        $this->assertEquals($price->getValue(), $model->price->getValue());
        $this->assertSame($price->getDateTime()->format('Y-m-d'), $model->price->getDateTime()->format('Y-m-d'));
        $this->assertSame('EUR', $model->price->getUnit()->getAlphabeticalCode());
        $this->assertSame('CZE', $model->price->getVAT()->getCountryCode());

        $this->assertSame(
            $price->getVAT()->getCategory() ?: null,
            $model->price_category ?: null
        );
    }

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

            protected $table = 'test_table';

            public function __construct(array $attributes = [], $fillable = [], $cast = '', $defaultProp = '')
            {
                $this->fillable = $fillable;
                $this->casts    = [$defaultProp => $cast];
                $this->visible  = $fillable;

                parent::__construct($attributes);

                $this->attributes['foreign_column'] = Carbon::now();
            }
        };
    }

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->afterApplicationCreated(
            function(): void {
                Schema::create('test_table', static function(Blueprint $table): void {
                    $table->id();
                    $table->integer('price')->default(0);
                    $table->string('price_currency', 3)->nullable();
                    $table->string('price_country', 3)->nullable();
                    $table->string('price_category')->nullable();
                    $table->date('price_date')->nullable();
                    $table->timestamps();
                    $table->date('foreign_column')->nullable();
                    $table->string('price_cntry', 3)->nullable();
                    $table->string('price_cat')->nullable();
                    $table->string('price_ccr', 3)->nullable();
                });
            }
        );
        parent::setUp();
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
