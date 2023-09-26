<?php

declare(strict_types=1);

namespace MiBo\Properties\Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Facade;
use MiBo\Prices\Price;
use MiBo\Properties\Providers\ConfigProvider;
use MiBo\Properties\Providers\FacadeProvider;
use MiBo\Properties\Providers\ServiceProvider;
use MiBo\Properties\Providers\TranslationProvider;
use MiBo\Properties\Tests\TestingData\Resolvers\VATResolver;
use MiBo\Prices\Units\Price\Currency;
use MiBo\Properties\Calculators\UnitConvertor;
use MiBo\VAT\Resolvers\ProxyResolver;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Class LaravelTestCase
 *
 * @package MiBo\Properties\Tests
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

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        if (!is_dir(__DIR__ . '/TestingData/bootstrap/cache')) {
            mkdir(__DIR__ . '/TestingData/bootstrap/cache', 0777, true);
        }

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
        $this->app->register(FacadeProvider::class);
        $this->app->register(ServiceProvider::class);

        $this->setUpTraits();

        foreach ($this->afterApplicationCreatedCallbacks as $callback) {
            $callback();
        }

        $this->setUpHasRun = true;

        ProxyResolver::setResolver(VATResolver::class);

        // Setting conversion rate between CZK and EUR => 1 EUR = 25 CZK
        UnitConvertor::$unitConvertors[\MiBo\Prices\Quantities\Price::class] = function(Price $price, Currency $unit) {
            switch ($price->getUnit()->getAlphabeticalCode()) {
                case 'EUR':
                    $price->getNumericalValue()->multiply(25);
                break;

                case 'USD':
                    $price->getNumericalValue()->multiply(20);
                break;

                case 'CZK':
                    $price->getNumericalValue()->multiply(1);
                break;

                default:
                    $price->getNumericalValue()->multiply(2);
                break;
            }

            switch ($unit->getAlphabeticalCode()) {
                case 'EUR':
                    $price->getNumericalValue()->divide(25);
                break;

                case 'USD':
                    $price->getNumericalValue()->divide(20);
                break;

                case 'CZK':
                    $price->getNumericalValue()->divide(1);
                break;

                default:
                    $price->getNumericalValue()->divide(2);
                break;
            }

            return $price->getNumericalValue();
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
