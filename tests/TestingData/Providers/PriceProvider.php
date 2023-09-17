<?php

declare(strict_types=1);

namespace MiBo\Prices\Tests\TestingData\Providers;

use Carbon\Carbon;
use Generator;
use MiBo\Prices\PositivePrice;
use MiBo\Prices\PositivePriceWithVAT;
use MiBo\Prices\Price;
use MiBo\Prices\PriceWithVAT;
use MiBo\Prices\Units\Price\Currency;
use MiBo\VAT\Enums\VATRate;
use MiBo\VAT\Resolvers\ProxyResolver;
use MiBo\VAT\VAT;

/**
 * Class PriceProvider
 *
 * @package MiBo\Prices\Tests\TestingData\Providers
 *
 * @author 3I666
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
class PriceProvider
{
    public static function dataForCreatingPrices(): Generator
    {
        foreach (self::providePrices() as $price) {
            yield [
                'price' => $price['price'],
                'value' => $price['value'],
                'data'  => [
                    'currency'       => $price['currency'] ?? null,
                    'category'       => $price['category'] ?? null,
                    'country'        => $price['country'] ?? null,
                    'date'           => $price['date'] ?? null,
                    'isVATIncluded'  => $price['isVatIncluded'] ?? null,
                    'isAnyVAT'       => $price['isAnyVat'] ?? null,
                    'strictPositive' => $price['strictPositive'] ?? null,
                ],
            ];
        }
    }

    /**
     * @return array<int|string, array{
     *     price: \Closure(): \MiBo\Prices\Price,
     *     value: float|int,
     *     currency?: non-empty-string,
     *     category?: string,
     *     country?: string,
     *     date?: \DateTimeInterface,
     *     isVatIncluded?: bool,
     *     isAnyVat?: bool,
     *     strictPositive?: bool,
     * }>
     */
    public static function providePrices(): array
    {
        return [
            [
                'price'    => static function(): Price {
                    return new Price(
                        0.0,
                        Currency::get('EUR'),
                        ProxyResolver::retrieveByCategory('', 'US'),
                        Carbon::now(),
                    );
                },
                'value'    => 0.0,
                'currency' => 'EUR',
            ],
            [
                'price' => static function(): Price {
                    return new Price(
                        0.0,
                        Currency::get('USD'),
                        ProxyResolver::retrieveByCategory('', 'US'),
                        Carbon::now(),
                    );
                },
                'value' => 0.0,
            ],
            [
                'price'    => static function(): Price {
                    return new Price(
                        0.0,
                        Currency::get('USD'),
                        ProxyResolver::retrieveByCategory('', 'US'),
                        Carbon::now(),
                    );
                },
                'value'    => 0.0,
                'currency' => 'USD',
            ],
            [
                'price' => static function(): Price {
                    return new Price(
                        10,
                        Currency::get('USD'),
                        ProxyResolver::retrieveByCategory('', 'US'),
                        Carbon::now(),
                    );
                },
                'value' => 10,
            ],
            [
                'price'    => static function(): Price {
                    return new Price(
                        0.0,
                        Currency::get('USD'),
                        ProxyResolver::retrieveByCategory('07', 'CZE'),
                        Carbon::now(),
                    );
                },
                'value'    => 0.0,
                'country'  => 'CZE',
                'category' => '07',
            ],
            [
                'price' => static function(): Price {
                    return new Price(
                        0.0,
                        Currency::get('USD'),
                        ProxyResolver::retrieveByCategory('', 'US'),
                        Carbon::now()->addYears(-1),
                    );
                },
                'value' => 0.0,
                'date'  => Carbon::now()->addYears(-1),
            ],
            [
                'price'         => static function(): Price {
                    return new PriceWithVAT(
                        10,
                        Currency::get('USD'),
                        ProxyResolver::retrieveByCategory('', 'US'),
                        Carbon::now(),
                    );
                },
                'value'         => 10,
                'isVatIncluded' => true,
            ],
            [
                'price'          => static function(): Price {
                    return new PositivePrice(
                        10,
                        Currency::get('USD'),
                        ProxyResolver::retrieveByCategory('', 'US'),
                        Carbon::now(),
                    );
                },
                'value'          => 10,
                'strictPositive' => true,
            ],
            [
                'price'          => static function(): Price {
                    return new PositivePriceWithVAT(
                        10,
                        Currency::get('USD'),
                        ProxyResolver::retrieveByCategory('', 'US'),
                        Carbon::now(),
                    );
                },
                'value'          => 10,
                'strictPositive' => true,
                'isVatIncluded'  => true,
            ],
            [
                'price'         => static function(): Price {
                    return new Price(
                        0.0,
                        Currency::get('EUR'),
                        ProxyResolver::retrieveByCategory('', 'US'),
                        Carbon::now(),
                    );
                },
                'value'         => 0.0,
                'currency'      => 'EUR',
                'isVatIncluded' => false,
            ],
            [
                'price'    => static function(): Price {
                    return new Price(
                        0.0,
                        Currency::get('EUR'),
                        VAT::get('US', VATRate::ANY),
                        Carbon::now(),
                    );
                },
                'value'    => 0.0,
                'currency' => 'EUR',
                'isAnyVat' => true,
            ],
        ];
    }
}
