<?php

declare(strict_types=1);

namespace MiBo\Prices\Data\Factories;

use Carbon\Carbon;
use DateTimeInterface;
use MiBo\Prices\PositivePrice;
use MiBo\Prices\PositivePriceWithVAT;
use MiBo\Prices\Price;
use MiBo\Prices\PriceWithVAT;
use MiBo\Prices\Units\Price\Currency;
use MiBo\VAT\Enums\VATRate;
use MiBo\VAT\Resolvers\ProxyResolver;
use MiBo\VAT\VAT;

/**
 * Class PriceFactory
 *
 * @package MiBo\Prices\Data\Factories
 *
 * @author 3I666
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
class PriceFactory
{
    private static ?self $instance = null;

    private float $value = 0.0;

    private string $currency = "";

    private string $category = "";

    private string $country = "";

    private DateTimeInterface $date;

    private bool $isVATIncluded = false;

    private bool $isAnyVAT = false;

    private bool $strictPositive = false;

    final private function __construct()
    {
        $this->date = Carbon::now();
    }

    public static function get(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        self::$instance->clear();

        return self::$instance;
    }

    /**
     * @param float $value
     *
     * @return static
     */
    public function setValue(float $value): static
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param string $currency
     *
     * @return static
     */
    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @param string $category
     *
     * @return static
     */
    public function setCategory(string $category): static
    {
        $this->category = $category;
        $this->isAnyVAT = false;

        return $this;
    }

    /**
     * @param string $country
     *
     * @return static
     */
    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @param \DateTimeInterface $date
     *
     * @return static
     */
    public function setDate(DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @param bool $isVATIncluded
     *
     * @return static
     */
    public function setIsVATIncluded(bool $isVATIncluded): static
    {
        $this->isVATIncluded = $isVATIncluded;

        return $this;
    }

    /**
     * @return static
     */
    public function isWithVAT(): static
    {
        return $this->setIsVATIncluded(true);
    }

    /**
     * @return static
     */
    public function isWithoutVAT(): static
    {
        return $this->setIsVATIncluded(false);
    }

    /**
     * @return static
     */
    public function setAnyVAT(): static
    {
        $this->isAnyVAT = true;

        return $this;
    }

    /**
     * @return static
     */
    public function strictlyPositive(): static
    {
        $this->strictPositive = true;

        return $this;
    }

    /**
     * @return \MiBo\Prices\Price
     */
    public function create(): Price
    {
        /** @var \MiBo\Prices\Price $price */
        $price = new ($this->getClassName())(
            $this->value,
            // @phpstan-ignore-next-line
            Currency::get($this->currency),
            $this->isAnyVAT ?
                VAT::get($this->country, VATRate::ANY) :
                ProxyResolver::retrieveByCategory($this->category, $this->country),
            $this->date
        );

        if (config('prices.defaults.printer') !== null) {
            // @phpstan-ignore-next-line
            $price->setPrinter(app(config('prices.defaults.printer')));
        }

        return $price;
    }

    /**
     * @return class-string<\MiBo\Prices\Price>
     */
    protected function getClassName(): string
    {
        if ($this->strictPositive && $this->isVATIncluded) {
            return PositivePriceWithVAT::class;
        }

        if ($this->isVATIncluded) {
            return PriceWithVAT::class;
        }

        if ($this->strictPositive) {
            return PositivePrice::class;
        }

        return Price::class;
    }

    protected function clear(): void
    {
        $this->date  = Carbon::now();
        $this->value = 0.0;
        // @phpstan-ignore-next-line
        $this->currency = config('prices.defaults.currency');
        $this->category = "";
        // @phpstan-ignore-next-line
        $this->country        = config('prices.defaults.country');
        $this->strictPositive = false;
        $this->isVATIncluded  = false;
        $this->isAnyVAT       = false;
    }
}
