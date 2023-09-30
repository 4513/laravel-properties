<?php

declare(strict_types=1);

namespace MiBo\Properties\Tests\Coverage\Providers;

use Generator;
use MiBo\Prices\Price;
use MiBo\Prices\Units\Price\Currency;
use MiBo\Properties\Length;
use MiBo\Properties\Pure;
use MiBo\Properties\ThermodynamicTemperature;
use MiBo\Properties\Units\Length\Meter;
use MiBo\Properties\Units\ThermodynamicTemperature\DegreeCelsius;

/**
 * Class PrinterProvider
 *
 * @package MiBo\Properties\Tests\Coverage\Providers
 *
 * @author 9I214
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
class PrinterProvider
{
    public static function getDataToFormat(): Generator
    {
        $data = [
            'Decimal point'                 => [
                'expected' => [
                    'en' => '1,000.00',
                    'sk' => '1 000,00',
                ],
                'value'    => new Pure(1_000),
                'decimals' => 2,
            ],
            'Decimal separator'             => [
                'expected' => [
                    'en' => '0.000,01',
                    'sk' => '0,00001',
                ],
                'value'    => new Pure(0.000_01),
                'decimals' => 5,
            ],
            'Thousand grouping'             => [
                'expected' => [
                    'en' => '1,234,567',
                    'sk' => '1 234 567',
                ],
                'value'    => new Pure(1_234_567),
                'decimals' => 0,
            ],
            'Thousand grouping (anti-zero)' => [
                'expected' => [
                    'en' => '123,456',
                    'sk' => '123 456',
                ],
                'value'    => new Pure(123_456),
                'decimals' => 0,
            ],
            'Property Flag'                 => [
                'expected' => [
                    'en' => '1,234.56 m',
                    'sk' => '1 234,56 m',
                ],
                'value'    => new Length(1_234.56, Meter::get()),
                'decimals' => 2,
            ],
            'Decimals (missing)'            => [
                'expected' => [
                    'en' => '12.00 m',
                    'sk' => '12,00 m',
                ],
                'value'    => new Length(12, Meter::get()),
                'decimals' => 2,
            ],
            'Decimals (ignored)'            => [
                'expected' => [
                    'en' => '12.1 m',
                    'sk' => '12,1 m',
                ],
                'value'    => new Length(12.12, Meter::get()),
                'decimals' => 1,
            ],
            'Negative number'               => [
                'expected' => [
                    'en' => '-21',
                    'sk' => '-21',
                ],
                'value'    => new Pure(-21),
                'decimals' => 0,
            ],
            'Celsius'                       => [
                'expected' => [
                    'en' => '12°C',
                    'sk' => '12°C',
                ],
                'value'    => new ThermodynamicTemperature(12, DegreeCelsius::get()),
                'decimals' => 0,
            ],
        ];

        foreach ($data as $key => $value) {
            foreach ($value['expected'] as $locale => $expected) {
                yield $locale . ' - ' . $key => [
                    $expected,
                    $locale,
                    $value['value'],
                    $value['decimals'] ?? null,
                ];
            }
        }
    }

    public static function getPricesToFormat(): Generator
    {
        $data = [
            'Price' => [
                'expected' => [
                    'en' => '$1,234.56',
                    'sk' => '1 234,56 $',
                ],
                'value'    => new Price(1234.56, Currency::get('USD')),
            ],
        ];

        foreach ($data as $key => $value) {
            foreach ($value['expected'] as $locale => $expected) {
                yield $locale . ' - ' . $key => [
                    $expected,
                    $locale,
                    $value['value'],
                    $value['decimals'] ?? null,
                ];
            }
        }
    }

    public static function getPricesToSimpleFormat(): Generator
    {
        $data = [
            'Price'  => [
                'expected' => [
                    'en' => '$1,234',
                    'sk' => '1 234 $',
                ],
                'value'    => new Price(1234.56, Currency::get('USD')),
            ],
            'Price2' => [
                'expected' => [
                    'en' => '$1,234.220,000,0',
                    'sk' => '1 234,22000 00 $',
                ],
                'value'    => new Price(1234.2222251, Currency::get('USD')),
                'decimals' => 7,
            ],
        ];

        foreach ($data as $key => $value) {
            foreach ($value['expected'] as $locale => $expected) {
                yield $locale . ' - ' . $key => [
                    $expected,
                    $locale,
                    $value['value'],
                    $value['decimals'] ?? null,
                ];
            }
        }
    }

    public static function getDataWithNullableDecimals(): Generator
    {
        $data = [
            'Decimal optional (present)'     => [
                'expected' => [
                    'en' => '0.01',
                    'sk' => '0,01',
                ],
                'value'    => new Pure(0.01),
                'decimals' => null,
            ],
            'Decimal optional (not present)' => [
                'expected' => [
                    'en' => '1,000',
                    'sk' => '1 000',
                ],
                'value'    => new Pure(1_000),
                'decimals' => null,
            ],
            'Celsius'                        => [
                'expected' => [
                    'en' => '12.1°C',
                    'sk' => '12,1°C',
                ],
                'value'    => new ThermodynamicTemperature(12.1, DegreeCelsius::get()),
                'decimals' => null,
            ],
            'Negative number'                => [
                'expected' => [
                    'en' => '-21',
                    'sk' => '-21',
                ],
                'value'    => new Pure(-21),
                'decimals' => null,
            ],
        ];

        foreach ($data as $key => $value) {
            foreach ($value['expected'] as $locale => $expected) {
                yield $locale . ' - ' . $key => [
                    $expected,
                    $locale,
                    $value['value'],
                    $value['decimals'] ?? null,
                ];
            }
        }
    }
}
