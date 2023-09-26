<?php

declare(strict_types=1);

namespace MiBo\Properties\Tests\Coverage\ProviderTests;

use MiBo\Properties\Data\Factories\DiscountFactory;
use MiBo\Properties\Data\Factories\PriceFactory;
use MiBo\Properties\Facades\Money;
use MiBo\Properties\Managers\MoneyManager;
use MiBo\Properties\Providers\FacadeProvider;
use MiBo\Properties\Tests\LaravelTestCase;

/**
 * Class FacadeProviderTest
 *
 * @package MiBo\Prices\Tests\Coverage\ProviderTests
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 *
 * @coversDefaultClass \MiBo\Properties\Providers\FacadeProvider
 */
class FacadeProviderTest extends LaravelTestCase
{
    /**
     * @small
     *
     * @covers ::provides
     * @covers ::register
     * @covers \MiBo\Properties\Facades\Money::getFacadeAccessor
     * @covers \MiBo\Properties\Managers\MoneyManager::__construct
     *
     * @return void
     */
    public function testRegister(): void
    {
        $this->app->registerDeferredProvider(FacadeProvider::class);

        $this->assertSame(['MiBoMoney'], (new FacadeProvider($this->app))->provides());
        $this->assertInstanceOf(MoneyManager::class, $this->app->get('MiBoMoney'));
        $this->assertInstanceOf(DiscountFactory::class, Money::discountFactory());
    }

    /**
     * @small
     *
     * @covers \MiBo\Properties\Managers\MoneyManager::__construct
     * @covers \MiBo\Properties\Managers\MoneyManager::priceFactory
     * @covers \MiBo\Properties\Managers\MoneyManager::discountFactory
     *
     * @return void
     */
    public function testMoneyManager(): void
    {
        $this->assertInstanceOf(PriceFactory::class, Money::priceFactory());
        $this->assertInstanceOf(DiscountFactory::class, Money::discountFactory());
    }
}
