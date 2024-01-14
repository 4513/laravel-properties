<?php

declare(strict_types=1);

namespace MiBo\Properties\Tests\Casting;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MiBo\Properties\Data\Casting\PriceAttribute;
use MiBo\Prices\Exceptions\NegativePriceException;
use MiBo\Properties\Tests\LaravelTestCase;
use function is_string;

/**
 * Class PriceRetrieveCastingTest
 *
 * @package MiBo\Properties\Tests\Casting
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
class PriceRetrieveCastingTest extends LaravelTestCase
{
    /**
     * @small
     *
     * @coversNothing
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
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testDefaultCaster(array $data): void
    {
        $caster = new PriceAttribute();
        $price  = $caster->get(
            new class extends Model {
            },
            'price',
            $data['price'],
            $data
        );

        $this->assertSame((float) ($data['price'] * 10 ** -2), (float) $price->getValue());
        $this->assertSame($data['price_currency'], $price->getUnit()->getAlphabeticalCode());
        $this->assertSame($data['price_country'], $price->getVAT()->getCountryCode());
        $this->assertSame($data['price_category'], $price->getVAT()->getClassification()->getCode());
        $this->assertSame(
            is_string($data['price_date']) ? $data['price_date'] : $data['price_date']?->format('Y-m-d'),
            $price->getDateTime()?->format('Y-m-d')
        );
    }

    /**
     * @small
     *
     * @coversNothing
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
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testInMajorCaster(array $data): void
    {
        $caster = new PriceAttribute('inMinor-false');
        $price  = $caster->get(
            new class extends Model {
            },
            'price',
            $data['price'],
            $data
        );

        $this->assertSame((float) $data['price'], (float) $price->getValue());
        $this->assertSame($data['price_currency'], $price->getUnit()->getAlphabeticalCode());
        $this->assertSame($data['price_country'], $price->getVAT()->getCountryCode());
        $this->assertSame($data['price_category'], $price->getVAT()->getClassification()->getCode());
        $this->assertSame(
            is_string($data['price_date']) ? $data['price_date'] : $data['price_date']?->format('Y-m-d'),
            $price->getDateTime()?->format('Y-m-d')
        );
    }

    /**
     * @small
     *
     * @coversNothing
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
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testInMinorCaster(array $data): void
    {
        $caster = new PriceAttribute('inMinor-true');
        $price  = $caster->get(
            new class extends Model {
            },
            'price',
            $data['price'],
            $data
        );

        $this->assertSame((float) ($data['price'] * 10 ** -2), (float) $price->getValue());
        $this->assertSame($data['price_currency'], $price->getUnit()->getAlphabeticalCode());
        $this->assertSame($data['price_country'], $price->getVAT()->getCountryCode());
        $this->assertSame($data['price_category'], $price->getVAT()->getClassification()->getCode());
        $this->assertSame(
            is_string($data['price_date']) ? $data['price_date'] : $data['price_date']?->format('Y-m-d'),
            $price->getDateTime()?->format('Y-m-d')
        );
    }

    /**
     * @small
     *
     * @coversNothing
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
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testCurrencyFixedCaster(array $data): void
    {
        $caster = new PriceAttribute('currency-SEK');
        $price  = $caster->get(
            new class extends Model {
            },
            'price',
            $data['price'],
            $data
        );

        $this->assertSame((float) ($data['price'] * 10 ** -2), (float) $price->getValue());
        $this->assertSame('SEK', $price->getUnit()->getAlphabeticalCode());
        $this->assertSame($data['price_country'], $price->getVAT()->getCountryCode());
        $this->assertSame($data['price_category'], $price->getVAT()->getClassification()->getCode());
        $this->assertSame(
            is_string($data['price_date']) ? $data['price_date'] : $data['price_date']?->format('Y-m-d'),
            $price->getDateTime()?->format('Y-m-d')
        );
    }

    /**
     * @small
     *
     * @coversNothing
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
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testCurrencyNotProvidedCaster(array $data): void
    {
        unset($data['price_currency']);

        $caster = new PriceAttribute();
        $price  = $caster->get(
            new class extends Model {
            },
            'price',
            $data['price'],
            $data
        );

        $this->assertSame((float) ($data['price'] * 10 ** -2), (float) $price->getValue());
        $this->assertSame('USD', $price->getUnit()->getAlphabeticalCode());
        $this->assertSame($data['price_country'], $price->getVAT()->getCountryCode());
        $this->assertSame($data['price_category'], $price->getVAT()->getClassification()->getCode());
        $this->assertSame(
            is_string($data['price_date']) ? $data['price_date'] : $data['price_date']?->format('Y-m-d'),
            $price->getDateTime()?->format('Y-m-d')
        );
    }

    /**
     * @small
     *
     * @coversNothing
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
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testCurrencyDifferentColumn1Caster(array $data): void
    {
        $caster = new PriceAttribute('currency-currency');
        $price  = $caster->get(
            new class extends Model {
            },
            'price',
            $data['price'],
            $data
        );

        $this->assertSame((float) ($data['price'] * 10 ** -2), (float) $price->getValue());
        $this->assertSame($data['currency'], $price->getUnit()->getAlphabeticalCode());
        $this->assertSame($data['price_country'], $price->getVAT()->getCountryCode());
        $this->assertSame($data['price_category'], $price->getVAT()->getClassification()->getCode());
        $this->assertSame(
            is_string($data['price_date']) ? $data['price_date'] : $data['price_date']?->format('Y-m-d'),
            $price->getDateTime()?->format('Y-m-d')
        );
    }

    /**
     * @small
     *
     * @coversNothing
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
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testCurrencyDifferentColumn2Caster(array $data): void
    {
        $caster = new PriceAttribute('currency-_currency');
        $price  = $caster->get(
            new class extends Model {
            },
            'price',
            $data['price'],
            $data
        );

        $this->assertSame((float) ($data['price'] * 10 ** -2), (float) $price->getValue());
        $this->assertSame($data['price_currency'], $price->getUnit()->getAlphabeticalCode());
        $this->assertSame($data['price_country'], $price->getVAT()->getCountryCode());
        $this->assertSame($data['price_category'], $price->getVAT()->getClassification()->getCode());
        $this->assertSame(
            is_string($data['price_date']) ? $data['price_date'] : $data['price_date']?->format('Y-m-d'),
            $price->getDateTime()?->format('Y-m-d')
        );
    }

    /**
     * @small
     *
     * @coversNothing
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
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testNegativesPossibleCaster(array $data): void
    {
        $caster = new PriceAttribute('positive-false');
        $price  = $caster->get(
            new class extends Model {
            },
            'price',
            $data['price'],
            $data
        );

        $this->assertSame((float) ($data['price'] * 10 ** -2), (float) $price->getValue());
        $this->assertSame($data['price_currency'], $price->getUnit()->getAlphabeticalCode());
        $this->assertSame($data['price_country'], $price->getVAT()->getCountryCode());
        $this->assertSame($data['price_category'], $price->getVAT()->getClassification()->getCode());
        $this->assertSame(
            is_string($data['price_date']) ? $data['price_date'] : $data['price_date']?->format('Y-m-d'),
            $price->getDateTime()?->format('Y-m-d')
        );
    }

    /**
     * @small
     *
     * @coversNothing
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
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testNegativesForbiddenCaster(array $data): void
    {
        if ($data['price'] < 0) {
            $this->expectException(NegativePriceException::class);
        }

        $caster = new PriceAttribute('positive-true');
        $price  = $caster->get(
            new class extends Model {
            },
            'price',
            $data['price'],
            $data
        );

        $this->assertSame((float) ($data['price'] * 10 ** -2), (float) $price->getValue());
        $this->assertSame($data['price_currency'], $price->getUnit()->getAlphabeticalCode());
        $this->assertSame($data['price_country'], $price->getVAT()->getCountryCode());
        $this->assertSame($data['price_category'], $price->getVAT()->getClassification()->getCode());
        $this->assertSame(
            is_string($data['price_date']) ? $data['price_date'] : $data['price_date']?->format('Y-m-d'),
            $price->getDateTime()?->format('Y-m-d')
        );
    }

    /**
     * @small
     *
     * @coversNothing
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
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testSpecifiedCategoryCaster(array $data): void
    {
        $caster = new PriceAttribute('category-08');
        $price  = $caster->get(
            new class extends Model {
            },
            'price',
            $data['price'],
            $data
        );

        $this->assertSame((float) ($data['price'] * 10 ** -2), (float) $price->getValue());
        $this->assertSame($data['price_currency'], $price->getUnit()->getAlphabeticalCode());
        $this->assertSame($data['price_country'], $price->getVAT()->getCountryCode());
        $this->assertSame('08', $price->getVAT()->getClassification()->getCode());
        $this->assertSame(
            is_string($data['price_date']) ? $data['price_date'] : $data['price_date']?->format('Y-m-d'),
            $price->getDateTime()?->format('Y-m-d')
        );
    }

    /**
     * @small
     *
     * @coversNothing
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
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testCategoryDifferentColumnCaster(array $data): void
    {
        $caster = new PriceAttribute('category-_cat');
        $price  = $caster->get(
            new class extends Model {
            },
            'price',
            $data['price'],
            $data
        );

        $this->assertSame((float) ($data['price'] * 10 ** -2), (float) $price->getValue());
        $this->assertSame($data['price_currency'], $price->getUnit()->getAlphabeticalCode());
        $this->assertSame($data['price_country'], $price->getVAT()->getCountryCode());
        $this->assertSame($data['price_cat'], $price->getVAT()->getClassification()->getCode());
        $this->assertSame(
            is_string($data['price_date']) ? $data['price_date'] : $data['price_date']?->format('Y-m-d'),
            $price->getDateTime()?->format('Y-m-d')
        );
    }

    /**
     * @small
     *
     * @coversNothing
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
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testCategoryEmptyCaster(array $data): void
    {
        $caster = new PriceAttribute('category-');
        $price  = $caster->get(
            new class extends Model {
            },
            'price',
            $data['price'],
            $data
        );

        $this->assertSame((float) ($data['price'] * 10 ** -2), (float) $price->getValue());
        $this->assertSame($data['price_currency'], $price->getUnit()->getAlphabeticalCode());
        $this->assertSame($data['price_country'], $price->getVAT()->getCountryCode());
        $this->assertEmpty($price->getVAT()->getClassification()->getCode());
        $this->assertSame(
            is_string($data['price_date']) ? $data['price_date'] : $data['price_date']?->format('Y-m-d'),
            $price->getDateTime()?->format('Y-m-d')
        );
    }

    /**
     * @small
     *
     * @coversNothing
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
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testCategoryCallbackCaster(array $data): void
    {
        $caster = new PriceAttribute();

        $caster::setCategoryCallback(
            function(bool $bool, Model $model, array $attributes): string {
                return (string) ($attributes['id'] ?? '');
            }
        );

        $price = $caster->get(
            new class extends Model {
            },
            'price',
            $data['price'],
            $data
        );

        $this->assertSame((float) ($data['price'] * 10 ** -2), (float) $price->getValue());
        $this->assertSame($data['price_currency'], $price->getUnit()->getAlphabeticalCode());
        $this->assertSame($data['price_country'], $price->getVAT()->getCountryCode());
        $this->assertSame((string) $data['id'], $price->getVAT()->getClassification()->getCode());
        $this->assertSame(
            is_string($data['price_date']) ? $data['price_date'] : $data['price_date']?->format('Y-m-d'),
            $price->getDateTime()?->format('Y-m-d')
        );
    }

    /**
     * @small
     *
     * @coversNothing
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
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testCountryCaster(array $data): void
    {
        $caster = new PriceAttribute('country-SVK');
        $price  = $caster->get(
            new class extends Model {
            },
            'price',
            $data['price'],
            $data
        );

        $this->assertSame((float) ($data['price'] * 10 ** -2), (float) $price->getValue());
        $this->assertSame($data['price_currency'], $price->getUnit()->getAlphabeticalCode());
        $this->assertSame('SVK', $price->getVAT()->getCountryCode());
        $this->assertSame($data['price_category'], $price->getVAT()->getClassification()->getCode());
        $this->assertSame(
            is_string($data['price_date']) ? $data['price_date'] : $data['price_date']?->format('Y-m-d'),
            $price->getDateTime()?->format('Y-m-d')
        );
    }

    /**
     * @small
     *
     * @coversNothing
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
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testCountryDifferentColumnCaster(array $data): void
    {
        $caster = new PriceAttribute('country-_cntry');
        $price  = $caster->get(
            new class extends Model {
            },
            'price',
            $data['price'],
            $data
        );

        $this->assertSame((float) ($data['price'] * 10 ** -2), (float) $price->getValue());
        $this->assertSame($data['price_currency'], $price->getUnit()->getAlphabeticalCode());
        $this->assertSame($data['price_cntry'], $price->getVAT()->getCountryCode());
        $this->assertSame($data['price_category'], $price->getVAT()->getClassification()->getCode());
        $this->assertSame(
            is_string($data['price_date']) ? $data['price_date'] : $data['price_date']?->format('Y-m-d'),
            $price->getDateTime()?->format('Y-m-d')
        );
    }

    /**
     * @small
     *
     * @coversNothing
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
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testCountryNotProvidedCaster(array $data): void
    {
        unset($data['price_country']);

        $caster = new PriceAttribute();
        $price  = $caster->get(
            new class extends Model {
            },
            'price',
            $data['price'],
            $data
        );

        $this->assertSame((float) ($data['price'] * 10 ** -2), (float) $price->getValue());
        $this->assertSame($data['price_currency'], $price->getUnit()->getAlphabeticalCode());
        $this->assertSame('US', $price->getVAT()->getCountryCode());
        $this->assertSame($data['price_category'], $price->getVAT()->getClassification()->getCode());
        $this->assertSame(
            is_string($data['price_date']) ? $data['price_date'] : $data['price_date']?->format('Y-m-d'),
            $price->getDateTime()?->format('Y-m-d')
        );
    }

    /**
     * @small
     *
     * @coversNothing
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
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testDateCaster(array $data): void
    {
        $caster = new PriceAttribute('date-2023-01-01');
        $price  = $caster->get(
            new class extends Model {
            },
            'price',
            $data['price'],
            $data
        );

        $this->assertSame((float) ($data['price'] * 10 ** -2), (float) $price->getValue());
        $this->assertSame($data['price_currency'], $price->getUnit()->getAlphabeticalCode());
        $this->assertSame($data['price_country'], $price->getVAT()->getCountryCode());
        $this->assertSame($data['price_category'], $price->getVAT()->getClassification()->getCode());
        $this->assertSame('2023-01-01', $price->getDateTime()?->format('Y-m-d'));
    }

    /**
     * @small
     *
     * @coversNothing
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
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testDateColumnCaster(array $data): void
    {
        $caster = new PriceAttribute('date-_date');
        $price  = $caster->get(
            new class extends Model {
            },
            'price',
            $data['price'],
            $data
        );

        $this->assertSame((float) ($data['price'] * 10 ** -2), (float) $price->getValue());
        $this->assertSame($data['price_currency'], $price->getUnit()->getAlphabeticalCode());
        $this->assertSame($data['price_country'], $price->getVAT()->getCountryCode());
        $this->assertSame($data['price_category'], $price->getVAT()->getClassification()->getCode());
        $this->assertSame(
            is_string($data['price_date']) ? $data['price_date'] : $data['price_date']?->format('Y-m-d'),
            $price->getDateTime()?->format('Y-m-d')
        );
    }

    /**
     * @small
     *
     * @coversNothing
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
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testDateColumnForeignCaster(array $data): void
    {
        $data['created_at'] = $data['price_date'];

        $caster = new PriceAttribute('date-created_at');
        $price  = $caster->get(
            new class extends Model {
                protected $fillable = ['created_at'];
            },
            'price',
            $data['price'],
            $data
        );

        $this->assertSame((float) ($data['price'] * 10 ** -2), (float) $price->getValue());
        $this->assertSame($data['price_currency'], $price->getUnit()->getAlphabeticalCode());
        $this->assertSame($data['price_country'], $price->getVAT()->getCountryCode());
        $this->assertSame($data['price_category'], $price->getVAT()->getClassification()->getCode());
        $this->assertSame(
            is_string($data['price_date']) ? $data['price_date'] : $data['price_date']?->format('Y-m-d'),
            $price->getDateTime()?->format('Y-m-d')
        );
    }

    /**
     * @small
     *
     * @coversNothing
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
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testDateNotProvidedCaster(array $data): void
    {
        unset($data['price_date']);

        $caster = new PriceAttribute();
        $price  = $caster->get(
            new class extends Model {
            },
            'price',
            $data['price'],
            $data
        );

        $this->assertSame((float) ($data['price'] * 10 ** -2), (float) $price->getValue());
        $this->assertSame($data['price_currency'], $price->getUnit()->getAlphabeticalCode());
        $this->assertSame($data['price_country'], $price->getVAT()->getCountryCode());
        $this->assertSame($data['price_category'], $price->getVAT()->getClassification()->getCode());
        $this->assertSame(
            Carbon::now()->format('Y-m-d'),
            $price->getDateTime()?->format('Y-m-d')
        );
    }

    /**
     * @small
     *
     * @coversNothing
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
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testAnyVATTrueCaster(array $data): void
    {
        $caster = new PriceAttribute('any-true');
        $price  = $caster->get(
            new class extends Model {
            },
            'price',
            $data['price'],
            $data
        );

        $this->assertSame((float) ($data['price'] * 10 ** -2), (float) $price->getValue());
        $this->assertSame($data['price_currency'], $price->getUnit()->getAlphabeticalCode());
        $this->assertSame($data['price_country'], $price->getVAT()->getCountryCode());
        $this->assertTrue($price->getVAT()->isAny());
        $this->assertSame(
            is_string($data['price_date']) ? $data['price_date'] : $data['price_date']?->format('Y-m-d'),
            $price->getDateTime()?->format('Y-m-d')
        );
    }

    /**
     * @small
     *
     * @coversNothing
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
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testAnyVATFalseCaster(array $data): void
    {
        $caster = new PriceAttribute('any-false');
        $price  = $caster->get(
            new class extends Model {
            },
            'price',
            $data['price'],
            $data
        );

        $this->assertSame((float) ($data['price'] * 10 ** -2), (float) $price->getValue());
        $this->assertSame($data['price_currency'], $price->getUnit()->getAlphabeticalCode());
        $this->assertSame($data['price_country'], $price->getVAT()->getCountryCode());
        $this->assertSame($data['price_category'], $price->getVAT()->getClassification()->getCode());
        $this->assertSame(
            is_string($data['price_date']) ? $data['price_date'] : $data['price_date']?->format('Y-m-d'),
            $price->getDateTime()?->format('Y-m-d')
        );
    }

    /**
     * @small
     *
     * @coversNothing
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
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testWithVATTrueCaster(array $data): void
    {
        $caster = new PriceAttribute('vat-true');
        $price  = $caster->get(
            new class extends Model {
            },
            'price',
            $data['price'],
            $data
        );

        $this->assertSame((float) ($data['price'] * 10 ** -2), round((float) $price->getValueWithVAT(), 2));
        $this->assertSame($data['price_currency'], $price->getUnit()->getAlphabeticalCode());
        $this->assertSame($data['price_country'], $price->getVAT()->getCountryCode());
        $this->assertSame($data['price_category'], $price->getVAT()->getClassification()->getCode());
        $this->assertSame(
            is_string($data['price_date']) ? $data['price_date'] : $data['price_date']?->format('Y-m-d'),
            $price->getDateTime()?->format('Y-m-d')
        );
    }

    /**
     * @small
     *
     * @coversNothing
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
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testWithVATFalseCaster(array $data): void
    {
        $caster = new PriceAttribute('vat-false');
        $price  = $caster->get(
            new class extends Model {
            },
            'price',
            $data['price'],
            $data
        );

        $this->assertSame((float) ($data['price'] * 10 ** -2), (float) $price->getValue());
        $this->assertSame($data['price_currency'], $price->getUnit()->getAlphabeticalCode());
        $this->assertSame($data['price_country'], $price->getVAT()->getCountryCode());
        $this->assertSame($data['price_category'], $price->getVAT()->getClassification()->getCode());
        $this->assertSame(
            is_string($data['price_date']) ? $data['price_date'] : $data['price_date']?->format('Y-m-d'),
            $price->getDateTime()?->format('Y-m-d')
        );
    }

    /**
     * @small
     *
     * @coversNothing
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
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testCombinationCaster(array $data): void
    {
        $caster = new PriceAttribute(
            'currency-XXX',
            'country-SVK',
            'vat-true',
            'category-07',
            'date-2020-01-01'
        );
        $price  = $caster->get(
            new class extends Model {
            },
            'price',
            $data['price'],
            $data
        );

        $this->assertSame((float) $data['price'], (float) $price->getValueWithVAT());
        $this->assertSame('XXX', $price->getUnit()->getAlphabeticalCode());
        $this->assertSame('SVK', $price->getVAT()->getCountryCode());
        $this->assertSame('07', $price->getVAT()->getClassification()->getCode());
        $this->assertSame('2020-01-01', $price->getDateTime()?->format('Y-m-d'));
    }

    /**
     * @small
     *
     * @coversNothing
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
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\PriceCastingProvider::getDatabaseData()
     */
    public function testUselessCastingCaster(array $data): void
    {
        $caster = new PriceAttribute();
        $price  = $caster->get(
            new class extends Model {
            },
            'price',
            $data['price'],
            $data
        );
        $price  = $caster->get(
            new class extends Model {
            },
            'price',
            $price,
            $data
        );

        $this->assertSame((float) ($data['price'] * 10 ** -2), (float) $price->getValue());
        $this->assertSame($data['price_currency'], $price->getUnit()->getAlphabeticalCode());
        $this->assertSame($data['price_country'], $price->getVAT()->getCountryCode());
        $this->assertSame($data['price_category'], $price->getVAT()->getClassification()->getCode());
        $this->assertSame(
            is_string($data['price_date']) ? $data['price_date'] : $data['price_date']?->format('Y-m-d'),
            $price->getDateTime()?->format('Y-m-d')
        );
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
