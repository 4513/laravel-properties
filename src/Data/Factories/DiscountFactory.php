<?php

declare(strict_types=1);

namespace MiBo\Prices\Data\Factories;

use Closure;
use iterable;
use MiBo\Prices\Contracts\Discountable;
use MiBo\Prices\Contracts\PriceInterface;
use MiBo\Prices\Exceptions\CouldNotApplyWholeAmountOfDiscountException;
use MiBo\Prices\PositivePrice;
use MiBo\Prices\PositivePriceWithVAT;
use MiBo\Prices\Price;
use MiBo\Prices\PriceWithVAT;
use MiBo\VAT\Enums\VATRate;
use ValueError;
use function gettype;
use function in_array;
use function is_array;
use function is_bool;
use function is_float;
use function is_int;

/**
 * Class DiscountFactory
 *
 * @package MiBo\Prices\Data\Factories
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
class DiscountFactory
{
    /**
     * Country for which the VAT rate applies for the given discount.
     *
     *  All the prices that are passed via Discountable interface are
     * converted to that country.
     *
     * **Default value:** `config('prices.defaults.country')`
     */
    public const OPT_COUNTRY = 'config-country';

    /**
     * Filter that is applied to the Discountable object.
     *
     *  The closure consists of one parameter that is the Discountable
     * object and returns boolean value. If the value is true, the
     * discount will be applied on the object.
     *
     * By default, all the objects are accepted.
     *
     *  Before this filter is applied, the list is filtered if the
     * discount is not applicable only for a specific VAT rate. In
     * case the Discountable object does not have the VAT rate (nor
     * its price's inner price), the object is ignored and next
     * Discountable object is processed.
     *
     * **Default value:**
     * ```
     * function (Discountable $discountable): bool {
     *     return true;
     * }
     * ```
     */
    public const OPT_FILTER = 'config-filter';

    /**
     *  Determines if the value of the discount is with VAT or without VAT.
     * *(OPT_VALUE)*.
     *
     *  If the value is with VAT, the value with VAT of each Discountable
     * object is used to calculate the discount. Otherwise, the value
     * without VAT is used.
     *
     *  If this value is set to *true*, the returned class of the discount
     * is of type *PositivePriceWithVAT*, *PriceWithVAT* otherwise.
     *
     * **Default value:** `false`
     */
    public const OPT_IS_VALUE_WITH_VAT = 'config-is_value_with_vat';

    /**
     * Determines the value of percentage discount *in [%]*.
     *
     *  If the value is set to *10*, the discount will be 10% of the
     * price of the Discountable object. If the value is set to *100*,
     * the discount will be 100% of the price of the Discountable
     * object.
     *
     *  This option is used only with combination of *OPT_TYPE* set to
     * *TYPE_PERCENTAGE*.
     *
     * **Default value:** `0`
     */
    public const OPT_PERCENTAGE_VALUE = 'config-percentage_value';

    /**
     *  Create discount only if the whole sum of the discount will be
     * applied.
     *
     *  This setting is useful when you want to apply discount of 10 EUR
     * and only if all the amount (10 EUR) must be applied. If the
     * filtered Discountable objects do not have the sum of the prices
     * to equal that or to be greater than that, the discount is not
     * created and an exception is thrown.
     *  If the value is set to *false* (default), the discount is created
     * always, containing only the sum of the prices of the filtered
     * Discountable objects that could be applied. Meaning, if the sum
     * of Discountable objects is 5 EUR, the discount will be created with
     * the value of 5 EUR instead of 10 EUR.
     *
     *  This option is fully compatible with the option *OPT_TYPE* set to
     * *TYPE_PERCENTAGE* and might be very helpful in some cases.
     *
     * **Default value:** `false`
     */
    public const OPT_REQUIRES_WHOLE_SUM_TO_USE = 'config-whole';

    /**
     * List of Discountable objects that will be discounted.
     *
     *  The list must contain Discountable objects only. The list is
     * filtered by the filter *(OPT_FILTER)* and the discount is applied
     * on the filtered list.
     *
     *  No sorting is being made, and the order of the object is in the
     * order as they are passed to the list.
     *
     * The value might be any iterable object.
     *
     * **Default value:** `[]`
     */
    public const OPT_SUBJECT = 'config-subject';

    /**
     * Type of the discount.
     *
     *  The type of the discount is either *TYPE_FIXED* or
     * *TYPE_PERCENTAGE*. The type *TYPE_FIXED* means that the value of
     * the discount is calculated as it is. On the other hand, the type
     * *TYPE_PERCENTAGE* means that the value of the discount is
     * calculated as a percentage of the sum of the prices of the
     * filtered Discountable objects.
     *
     * **Default value:** `TYPE_FIXED`
     */
    public const OPT_TYPE = 'config-type';

    /**
     * Value of the discount.
     *
     *  The maximum value of the discount that can be applied. The value
     * is either float, integer, or null. If the value is null, the value
     * is not limited. **Be aware that combination of this value set to
     * null and the option *OPT_TYPE* set to *TYPE_FIXED* might lead to
     * making every Discountable object free.**
     *
     *  In both types of *OPT_TYPE*, the value of the discount will not
     * be greater than this value.
     *
     *  If the value is set to zero (0), the discount will be empty.
     *
     * **Default value:** `0`
     */
    public const OPT_VALUE = 'config-value';

    /**
     * VAT rate that will be used to calculate the discount.
     *
     *  The VAT rate is used to calculate the VAT of the discount. The
     * VAT rate is compared with the VAT rates of the Discountable
     * object prices. If the VAT rate is of this setting does not match
     * the VAT rate of the price of the Discountable object, the object
     * is skipped (before its filtering) and the next object is processed.
     * If the Discountable object price has VAT rate of type Combined,
     * the price's inner prices are looped and compared.
     *
     *  If this setting is set to Any VAT rate, the VAT rate checking is
     * ignored and the discount is applied on any Discountable object that
     * is filtered.
     *
     *  This setting is handy when you want to apply discount only on
     * Discountable objects that have the same VAT rate as the discount.
     *
     *  Acceptable value of this setting is any Enum case of VATRate,
     * except of Combined.
     *
     * **Default value:** `Any VAT rate`
     */
    public const OPT_VAT = 'config-any_vat';

    /**
     * Type of the discount.
     *
     *  This type of the discount checks the value (either with VAT or
     * without VAT) of the Discountable object.
     *
     * Default.
     */
    public const TYPE_FIXED = "fixed";

    /**
     * Type of the discount.
     *
     *  This type of the discount checks the value (either with VAT or
     * without VAT) of the Discountable object and calculates the value
     * of the discount as a percentage of the sum of the prices of the
     * filtered Discountable objects.
     */
    public const TYPE_PERCENTAGE = "percentage";

    /**
     * @var array{
     *     config-country: string,
     *     config-filter: \Closure(\MiBo\Prices\Contracts\Discountable): bool,
     *     config-percentage_value: float|int,
     *     config-is_value_with_vat: bool,
     *     config-whole: bool,
     *     config-subject: iterable<\MiBo\Prices\Contracts\Discountable>,
     *     config-type: self::TYPE_*,
     *     config-value: float|int,
     *     config-vat: \MiBo\VAT\Enums\VATRate
     * }
     */
    private array $options = [];

    private static ?self $instance = null;

    final private function __construct()
    {
        $this->clear();
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
     * @param self::OPT_* $name
     * @param mixed $value
     *
     * @return static
     *
     * @phpcs:ignore Generic.Files.LineLength.TooLong
     * @phpstan-return ($name is self::OPT_VAT ? ($value is \MiBo\VAT\Enums\VATRate ? static : never) : ($name is self::OPT_VALUE|self::OPT_PERCENTAGE_VALUE ? ($value is float|int ? static : never) : ($name is self::OPT_TYPE ? ($value is self::TYPE_* ? static : never) : ($name is self::OPT_SUBJECT ? ($value is \iterable<\MiBo\Prices\Contracts\Discountable> ? static : never) : ($name is self::OPT_IS_VALUE_WITH_VAT|self::OPT_REQUIRES_WHOLE_SUM_TO_USE ? ($value is bool ? static : never) : ($name is self::OPT_FILTER ? ($value is (\Closure(\MiBo\Prices\Contracts\Discountable): bool) ? static : never) : ($name is self::OPT_COUNTRY ? ($value is string ? static : never) : never)))))))
     */
    final public function setOption(string $name, mixed $value): static
    {
        $message = null;

        switch ($name) {
            case self::OPT_COUNTRY:
                if (!is_string($value)) {
                    $message = sprintf(
                        "Option '%s' must be string, %s given.",
                        $name,
                        gettype($value)
                    );
                }
            break;

            case self::OPT_FILTER:
                if ($value !== null && !$value instanceof Closure) {
                    $message = sprintf(
                        "Option '%s' must be null or instance of Closure, %s given.",
                        $name,
                        gettype($value)
                    );
                }
            break;

            case self::OPT_IS_VALUE_WITH_VAT:
            case self::OPT_REQUIRES_WHOLE_SUM_TO_USE:
                if (!is_bool($value)) {
                    $message = sprintf(
                        "Option '%s' must be boolean, %s given.",
                        $name,
                        gettype($value)
                    );
                }
            break;

            case self::OPT_SUBJECT:
                if (!is_array($value) && !$value instanceof iterable) {
                    $message = sprintf(
                        "Option '%s' must be array or iterable, %s given.",
                        $name,
                        gettype($value)
                    );
                }
            break;

            case self::OPT_TYPE:
                if (!in_array($value, [self::TYPE_FIXED, self::TYPE_PERCENTAGE], true)) {
                    $message = sprintf(
                        "Option '%s' must be one of '%s' or '%s', '%s' given.",
                        $name,
                        self::TYPE_FIXED,
                        self::TYPE_PERCENTAGE,
                        $value
                    );
                }
            break;

            case self::OPT_PERCENTAGE_VALUE:
            case self::OPT_VALUE:
                if (!is_int($value) && !is_float($value)) {
                    $message = sprintf(
                        "Option '%s' must be either integer or a float, %s given.",
                        $name,
                        gettype($value)
                    );
                }
            break;

            case self::OPT_VAT:
                if (!$value instanceof VATRate || $value->isCombined()) {
                    $message = sprintf(
                        "Option '%s' must be instance of '%s' except of type COMBINED, %s given.",
                        $name,
                        VATRate::class,
                        gettype($value)
                    );
                }
            break;

            default:
            throw new ValueError('Unknown option name ' . $name . '!');
        }

        if ($message !== null) {
            throw new ValueError("Invalid value for option '$name': $message");
        }

        $this->options[$name] = $value;

        return $this;
    }

    final public function create(): PositivePrice|PositivePriceWithVAT
    {
        $discount = PriceFactory::get()
            ->setCountry($this->options[self::OPT_COUNTRY])
            ->strictlyPositive()
            ->setIsVATIncluded($this->options['config-is_value_with_vat'])
            ->create();

        $counter = $this->options[self::OPT_VALUE] ?: null;

        foreach ($this->options[self::OPT_SUBJECT] as $subject) {
            if (!$subject instanceof Discountable) {
                throw new ValueError('Subject must be instance of ' . Discountable::class . '!');
            }

            if ($counter !== null && round($counter, 5) <= 0.0) {
                break;
            }

            // Setting the compatible country.
            $subject->getPrice()->forCountry($this->options[self::OPT_COUNTRY]);

            $vat     = $this->options[self::OPT_VAT];
            $checked = null;

            if ($subject->getPrice()->getVAT()->isCombined()) {
                foreach ($subject->getPrice()->getNestedPrices() as $price) {
                    if ($vat->isNotAny() && !$vat->equals($price->getVAT()->getRate())) {
                        continue;
                    }

                    if ($checked === null) {
                        $checked = $this->options[self::OPT_FILTER]($subject);
                    }

                    if ($checked === false) {
                        break;
                    }

                    $counter = $this->apply($subject, $price, $discount, $counter);
                }

                continue;
            }

            if ($vat->isNotAny() && !$vat->equals($subject->getPrice()->getVAT()->getRate())) {
                continue;
            }

            $checked = $this->options[self::OPT_FILTER]($subject);

            if ($checked === false) {
                continue;
            }

            $counter = $this->apply($subject, $subject->getPrice(), $discount, $counter);
        }

        if ($counter !== null
            && !round($counter, 5) <= 0.0
            && $this->options[self::OPT_REQUIRES_WHOLE_SUM_TO_USE]
        ) {
            throw new CouldNotApplyWholeAmountOfDiscountException($counter);
        }

        /** @phpstan-var \MiBo\Prices\PositivePrice|\MiBo\Prices\PositivePriceWithVAT */
        return $discount;
    }

    /**
     * @param \MiBo\Prices\Contracts\Discountable $discountable
     * @param \MiBo\Prices\Contracts\PriceInterface $price
     * @param \MiBo\Prices\Contracts\PriceInterface $discount
     * @param int|float|null $counter
     *
     * @return ($counter is null ? null : int|float)
     */
    private function apply(
        Discountable $discountable,
        PriceInterface $price,
        PriceInterface $discount,
        int|float|null $counter
    ): int|float|null
    {
        match ($this->options[self::OPT_TYPE]) {
            self::TYPE_FIXED      => $counter = $this->applyFixed($discountable, $price, $discount, $counter),
            self::TYPE_PERCENTAGE => $counter = $this->applyPercentage($discountable, $price, $discount, $counter),
        };

        return $counter;
    }

    /**
     * @param \MiBo\Prices\Contracts\Discountable $discountable
     * @param \MiBo\Prices\Contracts\PriceInterface $price
     * @param \MiBo\Prices\Contracts\PriceInterface $discount
     * @param int|float|null $counter
     *
     * @return ($counter is null ? null : int|float)
     */
    private function applyFixed(
        Discountable $discountable,
        PriceInterface $price,
        PriceInterface $discount,
        int|float|null $counter
    ): int|float|null
    {
        if ($counter === null) {
            $discount->add($price);
            $discountable->registerDiscountPrice($price);

            return null;
        }

        $value = $this->options[self::OPT_IS_VALUE_WITH_VAT]
            ? $price->getValueWithVAT()
            : $price->getValue();

        if ($counter > $value) {
            $counter -= $value;

            $discount->add($price);
            $discountable->registerDiscountPrice($price);

            return $counter;
        }

        $price = $this->options[self::OPT_IS_VALUE_WITH_VAT]
            ? new PriceWithVAT($counter, $price->getUnit(), $price->getVAT())
            : new Price($counter, $price->getUnit(), $price->getVAT());

        $discount->add($price);
        $discountable->registerDiscountPrice($price);

        return 0;
    }

    /**
     * @param \MiBo\Prices\Contracts\Discountable $discountable
     * @param \MiBo\Prices\Contracts\PriceInterface $price
     * @param \MiBo\Prices\Contracts\PriceInterface $discount
     * @param int|float|null $counter
     *
     * @return ($counter is null ? null : int|float)
     */
    private function applyPercentage(
        Discountable $discountable,
        PriceInterface $price,
        PriceInterface $discount,
        int|float|null $counter
    ): int|float|null
    {
        $value = $this->options[self::OPT_IS_VALUE_WITH_VAT]
            ? $price->getValueWithVAT()
            : $price->getValue();
        $value = $value * $this->options[self::OPT_PERCENTAGE_VALUE] / 100;

        if ($counter === null) {
            $price = $this->options[self::OPT_IS_VALUE_WITH_VAT]
                ? new PriceWithVAT($value, $price->getUnit(), $price->getVAT())
                : new Price($value, $price->getUnit(), $price->getVAT());

            $discount->add($price);
            $discountable->registerDiscountPrice($price);

            return null;
        }

        if ($counter > $value) {
            $counter -= $value;

            $price = $this->options[self::OPT_IS_VALUE_WITH_VAT]
                ? new PriceWithVAT($value, $price->getUnit(), $price->getVAT())
                : new Price($value, $price->getUnit(), $price->getVAT());

            $discount->add($price);
            $discountable->registerDiscountPrice($price);

            return $counter;
        }

        $price = $this->options[self::OPT_IS_VALUE_WITH_VAT]
            ? new PriceWithVAT($counter, $price->getUnit(), $price->getVAT())
            : new Price($counter, $price->getUnit(), $price->getVAT());

        $discount->add($price);
        $discountable->registerDiscountPrice($price);

        return 0;
    }

    final protected function clear(): void
    {
        $this->options = [
            self::OPT_COUNTRY                   => config('prices.defaults.country'),
            self::OPT_FILTER                    => static function(): bool {
                return true;
            },
            self::OPT_IS_VALUE_WITH_VAT         => false,
            self::OPT_PERCENTAGE_VALUE          => 0,
            self::OPT_REQUIRES_WHOLE_SUM_TO_USE => false,
            self::OPT_SUBJECT                   => [],
            self::OPT_TYPE                      => self::TYPE_FIXED,
            self::OPT_VALUE                     => 0,
            self::OPT_VAT                       => VATRate::ANY,
        ];
    }
}
