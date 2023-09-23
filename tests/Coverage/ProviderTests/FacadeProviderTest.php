<?php

declare(strict_types=1);

namespace MiBo\Prices\Tests\Coverage\ProviderTests;

use MiBo\Prices\Data\Factories\DiscountFactory;
use MiBo\Prices\Data\Factories\PriceFactory;
use MiBo\Prices\Facades\Money;
use MiBo\Prices\Managers\MoneyManager;
use MiBo\Prices\Providers\FacadeProvider;
use MiBo\Prices\Tests\LaravelTestCase;

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
 * @coversDefaultClass \MiBo\Prices\Providers\FacadeProvider
 */
class FacadeProviderTest extends LaravelTestCase
{
    /**
     * @small
     *
     * @covers ::provides
     * @covers ::register
     * @covers \MiBo\Prices\Facades\Money::getFacadeAccessor
     * @covers \MiBo\Prices\Managers\MoneyManager::__construct
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
     * @covers \MiBo\Prices\Managers\MoneyManager::__construct
     * @covers \MiBo\Prices\Managers\MoneyManager::priceFactory
     * @covers \MiBo\Prices\Managers\MoneyManager::discountFactory
     *
     * @return void
     */
    public function testMoneyManager(): void
    {
        $this->assertInstanceOf(PriceFactory::class, Money::priceFactory());
        $this->assertInstanceOf(DiscountFactory::class, Money::discountFactory());
    }
}
