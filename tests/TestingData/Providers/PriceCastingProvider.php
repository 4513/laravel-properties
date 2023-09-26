<?php

declare(strict_types=1);

namespace MiBo\Properties\Tests\TestingData\Providers;

use Carbon\Carbon;
use Generator;
use MiBo\Properties\Data\Factories\PriceFactory;
use MiBo\Prices\Price;

/**
 * Class PriceCastingProvider
 *
 * @package MiBo\Properties\Tests\TestingData\Providers
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
class PriceCastingProvider
{
    public static function getApplicationData(): Generator
    {
        $list = [
            [
                function(): Price
                {
                    return PriceFactory::get()
                        ->setValue(10)
                        ->setCurrency('EUR')
                        ->setCountry('SVK')
                        ->setDate(
                            Carbon::now()->addDays(-11)->setMinute(0)->setSecond(0)->setHour(0)
                        )
                        ->setCategory('07')
                        ->create();
                },
            ],
            [
                function(): Price
                {
                    return PriceFactory::get()
                        ->setValue(20)
                        ->setCurrency('EUR')
                        ->setCountry('SVK')
                        ->setDate(
                            Carbon::now()->addDays(-365)->setMinute(0)->setSecond(0)->setHour(0)
                        )
                        ->setCategory('07')
                        ->create();
                },
            ],
            [
                function(): Price
                {
                    return PriceFactory::get()
                        ->setValue(25)
                        ->setCurrency('USD')
                        ->setCountry('SVK')
                        ->setDate(
                            Carbon::now()->addDays(-30)->setMinute(0)->setSecond(0)->setHour(0)
                        )
                        ->setCategory('07')
                        ->create();
                },
            ],
            [
                function(): Price
                {
                    return PriceFactory::get()
                        ->setValue(1500)
                        ->setCurrency('EUR')
                        ->setCountry('SVK')
                        ->setDate(
                            Carbon::now()->setMinute(0)->setSecond(0)->setHour(0),
                        )
                        ->setCategory('07')
                        ->create();
                },
            ],
            [
                function(): Price
                {
                    return PriceFactory::get()
                        ->setValue(3000)
                        ->setCurrency('EUR')
                        ->setCountry('SVK')
                        ->setDate(
                            Carbon::now()->addDays(-1)->setMinute(0)->setSecond(0)->setHour(0),
                        )
                        ->strictlyPositive()
                        ->setCategory('07')
                        ->create();
                },
            ],
            [
                function(): Price
                {
                    return PriceFactory::get()
                        ->setValue(200)
                        ->setCurrency('CZK')
                        ->setCountry('SVK')
                        ->setDate(
                            Carbon::now()->addDays(-11)->setMinute(0)->setSecond(0)->setHour(0)
                        )
                        ->setCategory('07')
                        ->create();
                },
            ],
            [
                function(): Price
                {
                    return PriceFactory::get()
                        ->setValue(25)
                        ->setCurrency('USD')
                        ->setCountry('SVK')
                        ->setDate(
                            Carbon::createFromFormat('Y-m-d', '2023-01-01')
                        )
                        ->strictlyPositive()
                        ->setCategory('07')
                        ->create();
                },
            ],
            [
                function(): Price
                {
                    return PriceFactory::get()
                        ->setValue(-1000)
                        ->setCurrency('EUR')
                        ->setCountry('SVK')
                        ->setDate(
                            Carbon::now()->addDays(-11)->setMinute(0)->setSecond(0)->setHour(0)
                        )
                        ->setCategory('07')
                        ->setAnyVAT()
                        ->create();
                },
            ],
            [
                function(): Price
                {
                    return PriceFactory::get()
                        ->setValue(-1000)
                        ->setCurrency('EUR')
                        ->setCountry('SVK')
                        ->setCategory('07')
                        ->create();
                },
            ],
            [
                function(): Price
                {
                    return PriceFactory::get()
                        ->setValue(10)
                        ->setCurrency('EUR')
                        ->setCountry('SVK')
                        ->setDate(
                            Carbon::now()->addDays(-11)->setMinute(0)->setSecond(0)->setHour(0)
                        )
                        ->setCategory('07')
                        ->isWithVAT()
                        ->create();
                },
            ],
            [
                function(): Price
                {
                    return PriceFactory::get()
                        ->setValue(20)
                        ->setCurrency('EUR')
                        ->setCountry('SVK')
                        ->setDate(
                            Carbon::now()->addDays(-365)->setMinute(0)->setSecond(0)->setHour(0)
                        )
                        ->setCategory('07')
                        ->isWithVAT()
                        ->create();
                },
            ],
            [
                function(): Price
                {
                    return PriceFactory::get()
                        ->setValue(25)
                        ->setCurrency('USD')
                        ->setCountry('SVK')
                        ->setDate(
                            Carbon::now()->addDays(-30)->setMinute(0)->setSecond(0)->setHour(0)
                        )
                        ->setCategory('07')
                        ->isWithVAT()
                        ->create();
                },
            ],
            [
                function(): Price
                {
                    return PriceFactory::get()
                        ->setValue(1500)
                        ->setCurrency('EUR')
                        ->setCountry('SVK')
                        ->setDate(
                            Carbon::now()->setMinute(0)->setSecond(0)->setHour(0),
                        )
                        ->setCategory('07')
                        ->isWithVAT()
                        ->create();
                },
            ],
            [
                function(): Price
                {
                    return PriceFactory::get()
                        ->setValue(3000)
                        ->setCurrency('EUR')
                        ->setCountry('SVK')
                        ->setDate(
                            Carbon::now()->addDays(-1)->setMinute(0)->setSecond(0)->setHour(0),
                        )
                        ->strictlyPositive()
                        ->setCategory('07')
                        ->isWithVAT()
                        ->create();
                },
            ],
            [
                function(): Price
                {
                    return PriceFactory::get()
                        ->setValue(200)
                        ->setCurrency('CZK')
                        ->setCountry('SVK')
                        ->setDate(
                            Carbon::now()->addDays(-11)->setMinute(0)->setSecond(0)->setHour(0)
                        )
                        ->setCategory('07')
                        ->isWithVAT()
                        ->create();
                },
            ],
            [
                function(): Price
                {
                    return PriceFactory::get()
                        ->setValue(25)
                        ->setCurrency('USD')
                        ->setCountry('SVK')
                        ->setDate(
                            Carbon::createFromFormat('Y-m-d', '2023-01-01')
                        )
                        ->strictlyPositive()
                        ->setCategory('07')
                        ->isWithVAT()
                        ->create();
                },
            ],
            [
                function(): Price
                {
                    return PriceFactory::get()
                        ->setValue(-1000)
                        ->setCurrency('EUR')
                        ->setCountry('SVK')
                        ->setDate(
                            Carbon::now()->addDays(-11)->setMinute(0)->setSecond(0)->setHour(0)
                        )
                        ->setCategory('07')
                        ->isWithVAT()
                        ->create();
                },
            ],
            [
                function(): Price
                {
                    return PriceFactory::get()
                        ->setValue(-1000)
                        ->setCurrency('EUR')
                        ->setCountry('SVK')
                        ->setCategory('07')
                        ->isWithVAT()
                        ->create();
                },
            ],
        ];

        foreach ($list as $item) {
            yield $item;
        }
    }

    public static function getDatabaseData(): Generator
    {
        $list = [
            [
                'price'          => 10,
                'price_currency' => 'EUR',
                'price_country'  => 'SVK',
                'price_date'     => Carbon::now()->addDays(-11)->setMinute(0)->setSecond(0)->setHour(0),
                'price_category' => '07',
                'currency'       => 'CHE',
                'country'        => 'CZE',
                'price_cntry'    => 'SLO',
                'price_cat'      => '06',
            ],
            [
                'price'          => 20,
                'price_currency' => 'EUR',
                'price_country'  => 'SVK',
                'price_date'     => Carbon::now()->addDays(-365)->setMinute(0)->setSecond(0)->setHour(0),
                'price_category' => '07',
                'currency'       => 'CHE',
                'country'        => 'CZE',
                'price_cntry'    => 'SLO',
                'price_cat'      => '06',
            ],
            [
                'price'          => 25,
                'price_currency' => 'USD',
                'price_country'  => 'SVK',
                'price_date'     => Carbon::now()->addDays(-30)->setMinute(0)->setSecond(0)->setHour(0),
                'price_category' => '07',
                'currency'       => 'CHE',
                'country'        => 'CZE',
                'price_cntry'    => 'SLO',
                'price_cat'      => '06',
            ],
            [
                'price'          => 1500,
                'price_currency' => 'EUR',
                'price_country'  => 'SVK',
                'price_date'     => Carbon::now()->setMinute(0)->setSecond(0)->setHour(0),
                'price_category' => '07',
                'currency'       => 'CHE',
                'country'        => 'CZE',
                'price_cntry'    => 'SLO',
                'price_cat'      => '06',
            ],
            [
                'price'          => 3000,
                'price_currency' => 'EUR',
                'price_country'  => 'SVK',
                'price_date'     => Carbon::now()->addDays(-1)->setMinute(0)->setSecond(0)->setHour(0),
                'price_category' => '07',
                'currency'       => 'CHE',
                'country'        => 'CZE',
                'price_cntry'    => 'SLO',
                'price_cat'      => '06',
            ],
            [
                'price'          => 200,
                'price_currency' => 'CZK',
                'price_country'  => 'SVK',
                'price_date'     => Carbon::now()->addDays(-1)->setMinute(0)->setSecond(0)->setHour(0),
                'price_category' => '07',
                'currency'       => 'CHE',
                'country'        => 'CZE',
                'price_cntry'    => 'SLO',
                'price_cat'      => '06',
            ],
            [
                'price'          => 25,
                'price_currency' => 'USD',
                'price_country'  => 'SVK',
                'price_date'     => '2023-01-01',
                'price_category' => '07',
                'currency'       => 'CHE',
                'country'        => 'CZE',
                'price_cntry'    => 'SLO',
                'price_cat'      => '06',
            ],
            [
                'price'          => -1000,
                'price_currency' => 'EUR',
                'price_country'  => 'SVK',
                'price_date'     => Carbon::now()->addDays(-11)->setMinute(0)->setSecond(0)->setHour(0),
                'price_category' => '07',
                'currency'       => 'CHE',
                'country'        => 'CZE',
                'price_cntry'    => 'SLO',
                'price_cat'      => '06',
            ],
        ];

        $i = 1;

        foreach ($list as $item) {
            $item['id'] = $i;

            yield [$item];

            $i++;
        }
    }
}
