<?php

declare(strict_types=1);

namespace MiBo\Properties\Providers;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use MiBo\Currencies\CurrencyProvider;
use MiBo\Currencies\ListLoader;
use MiBo\Prices\Calculators\PriceCalc;
use MiBo\Prices\Contracts\PriceInterface;
use MiBo\Prices\Quantities\Price;
use MiBo\Prices\Units\Price\Currency;
use MiBo\Properties\Calculators\PropertyCalc;
use MiBo\Properties\Calculators\UnitConvertor;
use MiBo\Properties\Value;
use MiBo\VAT\Resolvers\ProxyResolver;
use function is_string;

/**
 * Class ServiceProvider
 *
 * @package MiBo\Properties\Providers
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
final class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->registerAllowedQuantities();
        $this->registerDefaultUnits();
        $this->registerVATResolver();
        $this->registerVATConvertor();
        $this->registerPriceCalculator();
        $this->registerPriceConvertor();
        $this->registerPriceComparer();
        $this->registerCurrencyListLoader();
    }

    /**
     * Registers default quantities.
     *
     * @return void
     */
    protected function registerAllowedQuantities(): void
    {
        $config = $this->app['config']['properties.allowedQuantities'] ?? [];

        if (empty($config)) {
            return;
        }

        PropertyCalc::$quantities = $config;
    }

    /**
     * Registers default units.
     *
     * @return void
     */
    protected function registerDefaultUnits(): void
    {
        $config = $this->app['config']['properties.defaultUnits'] ?? [];

        /**
         * @var \MiBo\Properties\Contracts\Quantity $quantity
         * @var \MiBo\Properties\Units\Amount\Unit $unit
         */
        foreach ($config as $quantity => $unit) {
            // @phpstan-ignore-next-line
            if (!class_exists($quantity)) {
                continue;
            }

            $quantity::setDefaultUnit($unit::get());
        }

        Currency::$defaultCurrency = $this->app['config']['prices.currency.default'] ?? Currency::$defaultCurrency;
    }

    /**
     * Registers VAT resolver.
     *
     * @return void
     */
    protected function registerVATResolver(): void
    {
        /** @var class-string<\MiBo\VAT\Contracts\Resolver> $config */
        $config = $this->app['config']['prices.vat.resolver'];

        ProxyResolver::setResolver($config);
    }

    /**
     * Registers VAT resolver.
     *
     * @return void
     */
    protected function registerVATConvertor(): void
    {
        /** @var class-string<\MiBo\VAT\Contracts\Convertor> $config */
        $config = $this->app['config']['prices.vat.convertor'] ?? $this->app['config']['prices.vat.resolver'];

        ProxyResolver::setConvertor($config);
    }

    /**
     * Registers price convertor.
     *
     * @return void
     */
    protected function registerPriceConvertor(): void
    {
        /** @var callable|class-string<\MiBo\Currency\Rates\Contracts\ExchangerInterface> $config */
        $config = $this->app['config']['prices.convertor'];
        $config = is_string($config) && class_exists($config)
            ? static function(PriceInterface $price, Currency $currency) use ($config): Value {
                /** @var \MiBo\Currency\Rates\Contracts\ExchangerInterface $config */
                $config = new $config();

                // @phpstan-ignore-next-line
                return $price->multiply($config->getRateFor($currency, $price->getUnit()))->getNumericalValue();
            }
            // @phpstan-ignore-next-line
            : $config(...);

        UnitConvertor::$unitConvertors[Price::class] = $config;
    }

    /**
     * Register currency list loader.
     *
     * @return void
     */
    protected function registerCurrencyListLoader(): void
    {
        // @phpstan-ignore-next-line
        $this->app->bind(ListLoader::class, $this->app['config']['prices.currency.loader']);

        /** @var \MiBo\Currencies\CurrencyProvider|null $provider */
        $provider = $this->app->make($this->app['config']['prices.currency.provider']);

        Currency::setCurrencyProvider($provider);
    }

    /**
     * Registers price calculator.
     *
     * @return void
     */
    protected function registerPriceCalculator(): void
    {
        /** @var class-string<\MiBo\Prices\Contracts\PriceCalculatorHelper>|null $config */
        $config = $this->app['config']['prices.calculator'] ?? null;

        if ($config === null) {
            return;
        }

        /** @var \MiBo\Prices\Contracts\PriceCalculatorHelper $helper */
        $helper = $this->app->make($config);

        PriceCalc::setCalculatorHelper($helper);
    }

    /**
     * Registers price comparer.
     *
     * @return void
     */
    protected function registerPriceComparer(): void
    {
        /** @var class-string<\MiBo\Prices\Contracts\PriceComparer>|null $config */
        $config = $this->app['config']['prices.comparer'] ?? null;

        if ($config === null) {
            return;
        }

        /** @var \MiBo\Prices\Contracts\PriceComparer $comparer */
        $comparer = $this->app->make($config);

        PriceCalc::setComparerHelper($comparer);
    }
}
