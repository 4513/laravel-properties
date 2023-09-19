<?php

declare(strict_types=1);

namespace MiBo\Prices\Tests;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Facade;
use MiBo\Prices\Price;
use MiBo\Prices\Providers\ConfigProvider;
use MiBo\Prices\Providers\TranslationProvider;
use MiBo\Prices\Tests\TestingData\Resolvers\VATResolver;
use MiBo\Prices\Units\Price\Currency;
use MiBo\Properties\Calculators\UnitConvertor;
use MiBo\VAT\Resolvers\ProxyResolver;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Class LaravelTestCase
 *
 * @package MiBo\Prices\Tests
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
class LaravelTestCase extends TestCase
{
    /**
     * @inheritDoc
     */
    public function createApplication(): HttpKernelInterface|Application
    {
        /** @var \Illuminate\Contracts\Foundation\Application $app */
        $app = include __DIR__ . '/TestingData/bootstrap/app.php';

        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->beforeApplicationDestroyedCallbacks[] = function(): void {
            try {
                $this->artisan("clear-compiled");
            } catch (BindingResolutionException) { // @phpcs:ignore
            }
        };

        static::$latestResponse = null;

        Facade::clearResolvedInstances();

        if (! $this->app) {
            $this->refreshApplication();
        }

        $this->app->register(ConfigProvider::class);
        $this->app->register(TranslationProvider::class);

        $this->setUpTraits();

        foreach ($this->afterApplicationCreatedCallbacks as $callback) {
            $callback();
        }

        $this->setUpHasRun = true;

        ProxyResolver::setResolver(VATResolver::class);

        // Setting conversion rate between CZK and EUR => 1 EUR = 25 CZK
        UnitConvertor::$unitConvertors[\MiBo\Prices\Quantities\Price::class] = function(Price $price, Currency $unit) {
            if ($price->getUnit()->getName() === "Euro" && $unit->getName() === "Czech Koruna") {
                return $price->getNumericalValue()->multiply(25);
            } else if ($price->getUnit()->is($unit)) {
                return $price->getNumericalValue();
            }

            return $price->getNumericalValue()->divide(25);
        };
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        $this->artisan('clear-compiled');

        parent::tearDown();
    }
}
