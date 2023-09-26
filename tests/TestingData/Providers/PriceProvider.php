<?php

declare(strict_types=1);

namespace MiBo\Properties\Tests\TestingData\Providers;

use Carbon\Carbon;
use Generator;
use MiBo\Properties\Contracts\Discountable;
use MiBo\Prices\Contracts\PriceInterface;
use MiBo\Properties\Data\Factories\DiscountFactory;
use MiBo\Properties\Data\Factories\PriceFactory;
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
 * @package MiBo\Properties\Tests\TestingData\Providers
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

    public static function provideFailingOptionList(): Generator
    {
        $list = [
            DiscountFactory::OPT_VALUE . ' not numeric'                       => [
                DiscountFactory::OPT_VALUE,
                'some random string',
            ],
            DiscountFactory::OPT_VALUE . ' boolean'                           => [
                DiscountFactory::OPT_VALUE,
                true,
            ],
            DiscountFactory::OPT_TYPE . ' not supported'                      => [
                DiscountFactory::OPT_TYPE,
                'forbidden',
            ],
            DiscountFactory::OPT_VAT . ' not a VAT'                           => [
                DiscountFactory::OPT_VAT,
                'not a VAT',
            ],
            DiscountFactory::OPT_VAT . ' combined - forbidden'                => [
                DiscountFactory::OPT_VAT,
                VATRate::COMBINED,
            ],
            DiscountFactory::OPT_FILTER . ' not a closure'                    => [
                DiscountFactory::OPT_FILTER,
                'not a closure',
            ],
            DiscountFactory::OPT_PERCENTAGE_VALUE . ' not numeric'            => [
                DiscountFactory::OPT_PERCENTAGE_VALUE,
                'not a number',
            ],
            DiscountFactory::OPT_IS_VALUE_WITH_VAT . ' not a boolean'         => [
                DiscountFactory::OPT_IS_VALUE_WITH_VAT,
                'not a boolean',
            ],
            DiscountFactory::OPT_SUBJECT . ' not iterable'                    => [
                DiscountFactory::OPT_SUBJECT,
                'not iterable',
            ],
            DiscountFactory::OPT_COUNTRY . ' not a string'                    => [
                DiscountFactory::OPT_COUNTRY,
                123,
            ],
            DiscountFactory::OPT_REQUIRES_WHOLE_SUM_TO_USE . ' not a boolean' => [
                DiscountFactory::OPT_REQUIRES_WHOLE_SUM_TO_USE,
                'not a boolean',
            ],
            'Invalid option'                                                  => [
                'invalid option',
                'some value',
            ],
        ];

        foreach ($list as $name => $value) {
            yield $name => $value;
        }
    }

    public static function provideDiscountableList(): Generator
    {
        yield 'Empty list' => [static fn() => []];
        yield 'List of 1 item' => [static fn() => self::getDiscountableList(25)];

        for ($i = 0; $i < 1_000; $i++) {
            $num = rand(0, 25 * 200);

            yield '#' . $i => [
                function() use ($num) {
                    return self::getDiscountableList($num);
                },
            ];
        }
    }

    protected static function getDiscountableList(int $sum): array
    {
        $sum  -= $sum % 25;
        $count = $sum / 25;
        $list  = [];

        for ($i = $count; $i > 0; $i--) {
            $list[] = self::createDiscountableObject(
                PriceFactory::get()->setValue(25)->create()
            );
        }

        return $list;
    }

    private static function createDiscountableObject(PriceInterface $price): Discountable
    {
        return new class ($price) implements Discountable {
            private PriceInterface $price;

            private PriceInterface $discount;

            public function __construct(PriceInterface $price)
            {
                $this->price = $price;
            }

            public function registerDiscountPrice(PriceInterface $discount): void
            {
                $this->price->subtract($discount);
                $this->discount = $discount;
            }

            public function getPrice(): PriceInterface
            {
                return $this->price;
            }

            public function getDiscountedPrice(): PriceInterface
            {
                return $this->discount;
            }
        };
    }
}
