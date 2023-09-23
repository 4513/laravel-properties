<?php

declare(strict_types=1);

namespace MiBo\Prices\Tests\Coverage\Factories;

use Closure;
use MiBo\Prices\Tests\Factories\PriceFactoryTest as BaseTest;

/**
 * Class PriceFactoryTest
 *
 * @package MiBo\Prices\Tests\Coverage\Factories
 *
 * @author 3I666
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 *
 * @coversDefaultClass \MiBo\Prices\Data\Factories\PriceFactory
 */
class PriceFactoryTest extends BaseTest
{
    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     * @covers ::getClassName
     * @covers ::create
     * @covers ::setValue
     * @covers ::setCurrency
     * @covers ::setCategory
     * @covers ::setCountry
     * @covers ::setDate
     * @covers ::setIsVATIncluded
     * @covers ::setAnyVAT
     * @covers ::strictlyPositive
     * @covers ::isWithVAT
     * @covers ::isWithoutVAT
     * @covers ::clear
     * @covers \MiBo\Prices\Providers\ConfigProvider::register
     * @covers \MiBo\Prices\Providers\ConfigProvider::publishConfigurations
     *
     * @param \Closure(): \MiBo\Prices\Price $createExpectedPrice
     * @param float|int $value
     * @param array{
     *     currency?: non-empty-string,
     *     category?: string,
     *     country?: string,
     *     date?: \DateTimeInterface,
     *     isVATIncluded?: bool,
     *     isAnyVat?: bool,
     *     strictPositive?: true|null,
     * } $data
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\Coverage\Providers\PriceProvider::dataForCreatingPrices()
     */
    public function testCreatingPrice(
        Closure $createExpectedPrice,
        float|int $value,
        array $data
    ): void
    {
        parent::testCreatingPrice($createExpectedPrice, $value, $data);
    }
}
