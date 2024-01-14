<?php

declare(strict_types=1);

namespace MiBo\Properties\Tests\Coverage\ProviderTests;

use MiBo\Properties\Providers\ServiceProvider;
use MiBo\Prices\Quantities\Price;
use MiBo\Properties\Tests\LaravelTestCase;
use MiBo\Properties\Calculators\PropertyCalc;
use MiBo\Properties\Calculators\UnitConvertor;
use MiBo\Properties\Quantities\Length;
use MiBo\Properties\Tests\TestingData\Resolvers\VATResolver;
use MiBo\Properties\Units\Length\Foot;
use stdClass;

/**
 * Class ServiceProviderTest
 *
 * @package MiBo\Properties\Tests\Coverage\ProviderTests
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 *
 * @coversDefaultClass \MiBo\Properties\Providers\ServiceProvider
 */
class ServiceProviderTest extends LaravelTestCase
{
    /**
     * @small
     *
     * @covers ::register
     * @covers ::registerPriceConvertor
     * @covers ::registerVATResolver
     * @covers ::registerVATConvertor
     * @covers ::registerDefaultUnits
     * @covers ::registerAllowedQuantities
     * @covers ::registerCurrencyListLoader
     * @covers ::registerPriceCalculator
     * @covers ::registerPriceComparer
     *
     * @return void
     */
    public function testDefaultRegister(): void
    {
        $this->app['config']['prices.vat.resolver'] = VATResolver::class;

        $provider = new ServiceProvider($this->app);
        $provider->register();
        $this->assertArrayHasKey(Price::class, UnitConvertor::$unitConvertors);
    }

    /**
     * @small
     *
     * @covers ::registerPriceConvertor
     * @covers ::registerDefaultUnits
     * @covers ::registerAllowedQuantities
     *
     * @return void
     */
    public function testUpdatedRegister(): void
    {
        $this->app['config']['prices.vat.resolver']          = VATResolver::class;
        $this->app['config']['prices.convertor']             = stdClass::class;
        $this->app['config']['properties.defaultUnits']      = [Length::class => Foot::class];
        $this->app['config']['properties.allowedQuantities'] = [Price::class];

        $provider = new ServiceProvider($this->app);
        $provider->register();
        $this->assertCount(1, PropertyCalc::$quantities);

        $this->app['config']['prices.convertor']             = [
            self::class,
            'whatDoIKnow',
        ];
        $this->app['config']['properties.allowedQuantities'] = [];

        $provider = new ServiceProvider($this->app);
        $provider->register();
        $this->assertCount(1, PropertyCalc::$quantities);
    }

    /**
     * @internal
     *
     * @return void
     */
    public static function whatDoIKnow(): void
    {
    }
}
