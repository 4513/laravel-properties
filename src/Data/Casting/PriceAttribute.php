<?php

declare(strict_types=1);

namespace MiBo\Properties\Data\Casting;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use MiBo\Properties\Classifications\Creator;
use MiBo\Properties\Data\Factories\PriceFactory;
use MiBo\Prices\Exceptions\NegativePriceException;
use MiBo\Prices\Price;
use MiBo\Prices\Units\Price\Currency;
use ValueError;
use function is_int;
use function is_string;

/**
 * Class PriceAttribute
 *
 * *This Attribute casts price and its properties from and to database.*
 *
 * Synopsys:
 * * \MiBo\Properties\Data\Casting\PriceAttribute::class
 * * \MiBo\Properties\Data\Casting\PriceAttribute::class . [':attr-value' . [',:attr-value' ...]]
 *
 * Example:
 * * \MiBo\Properties\Data\Casting\PriceAttribute::class . ':currency-EUR,date-created_at,inMinor-false'
 *
 * **Currency** - Currency code (ISO 4217)
 * <ul><li>Priorities
 *    <ol><li> Currency code passed to constructor <i>(specified by cast)</i></li>
 *    <li> Currency code passed as an attribute of the Model <i>($key . '_currency')</i></li>
 *    <li> Currency code from configuration file <i>('prices.currency.default')</i></li></ol></li>
 * <li>Setting
 *    <ul><li>Specified currency: <i>EUR or USD...</i></li>
 *    <li>Specified column suffix: <i>_currency or _my_currency...</li>
 *    <li>Specified column: <i>currency or clmn_currency...</li></ul></li></ul>
 * </ul>
 * **Positives** - Non negative price only
 * <ul><li>Setting
 *    <ul><li>Any value (default): false</li>
 *    <li>Only positive and 0: true</li></ul></li></ul>
 * **Category** - Category of the price
 * <ul><li>Priorities
 *    <ol><li> Category callback <i>closure(Model, array $attributes, string $key): string</i></li>
 *    <li> Category passed to constructor <i>(specified by cast)</i></li>
 *    <li> null</li></ol></li>
 * <li>Setting
 *    <ul><li>Specified column prefix: <i>_category or _my_category...</i></li>
 *    <li>Specified column: <i>category or clmn_category...</i></li></ul></li></ul>
 * **Country** - Country for VAT (ISO 3166-1 alpha-2)
 * <ul><li>Priorities
 *   <ol><li> Country passed to constructor <i>(specified by cast)</i></li>
 *   <li> Country passed as an attribute of the Model <i>($key . '_country')</i></li>
 *   <li> Country from configuration file <i>('prices.vat.country')</i></li></ol></li>
 * <li>Setting
 *   <ul><li>Specified country: <i>CZ or SK...</i></li>
 *   <li>Specified column suffix: <i>_country or _my_country...</li>
 *   <li>Specified column: <i>country or clmn_country...</li></ul></li></ul>
 * **Date** - Date for VAT
 * <ul><li>Priorities
 *   <ol><li> Date passed to constructor <i>(specified by cast)</i></li>
 *   <li> Date passed as an attribute of the Model <i>($key . '_date')</i></li>
 *   <li> Current date</li></ol></li>
 * <li>Setting
 *   <ul><li>Specified column: <i>created_at</i></li>
 *   <li>Specified column suffix: <i>_date or _my_date...</li></ul></li></ul>
 * **Any** - Any VAT rate setting
 * <ul><li>Setting
 *   <ul><li>Any value (default): false</li>
 *   <li>Any VAT rate: true</li></ul></li></ul>
 * **VAT** - Price stored with VAT
 * <ul><li>Setting
 *   <ul><li>Price stored with VAT: true</li>
 *   <li>Price stored without VAT (default): false</li></ul></li></ul>
 * **InMinor** - Price stored in minor units
 * <ul><li>Setting
 *   <ul><li>Price stored in minor units (default): true</li>
 *   <li>Price stored in major units: false</li></ul></li></ul>
 *
 *
 * @package MiBo\Properties\Data\Casting
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @implements \Illuminate\Contracts\Database\Eloquent\CastsAttributes<\MiBo\Prices\Price, \MiBo\Prices\Price>
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
class PriceAttribute implements CastsAttributes
{
    private const DEFAULTS = [
        'currency' => '_currency',
        'positive' => 'false',
        'category' => '_category',
        'country'  => '_country',
        'date'     => '_date',
        'any'      => 'false',
        'vat'      => 'false',
        'inMinor'  => 'true',
    ];

    private static ?Closure $categoryCallback = null;

    /**
     * @var array{
     *     currency: non-empty-string|null,
     *     positive: bool,
     *     category: non-empty-string,
     *     country: non-empty-string,
     *     date: non-empty-string,
     *     any: bool,
     *     vat: bool,
     *     inMinor: bool
     * }
     */
    private array $config;

    public function __construct(string ...$segments)
    {
        foreach ($segments as $key => $segment) {
            $arguments = explode('-', $segment, 2);
            $argument0 = $arguments[0];
            unset($arguments[0]);
            $segments[$argument0] = implode('-', $arguments);
            unset($segments[$key]);
        }

        $this->config = array_merge(self::DEFAULTS, $segments);
    }

    /**
     * Sets the attribute Price on the Model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model The model containing the price attribute.
     * @param string $key Key of the price.
     * @param float|\MiBo\Prices\Price $value Value of the price.
     * @param array<string, mixed> $attributes All attributes of the Model.
     *
     * @return \MiBo\Prices\Price
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): Price
    {
        if ($value instanceof Price) {
            return $value;
        }

        $factory = PriceFactory::get();

        if ($this->config["positive"] === 'true') {
            $factory->strictlyPositive();
        }

        $category = str_starts_with($this->config['category'], '_')
            && key_exists($key . $this->config['category'], $attributes)
            ? $attributes[$key . $this->config['category']]
            : (
                str_starts_with($this->config['category'], '_') ? null : $this->config['category']
            );

        if (self::$categoryCallback !== null) {
            $category = (self::$categoryCallback)(true, $model, $attributes, $key, $value);
        }

        $country  = str_starts_with($this->config["country"], "_") ?
            ($attributes[$key . $this->config["country"]] ?? '') :
            $this->config["country"];
        $country  = key_exists($country, $attributes) ? $attributes[$country] : $country;
        $country  = empty($country) ? config('prices.vat.country') : $country;
        $currency = str_starts_with($this->config["currency"], "_") ?
            ($attributes[$key . $this->config["currency"]] ?? '') :
            $this->config["currency"];
        $currency = !empty($currency) && key_exists($currency, $attributes) ?
            $attributes[$currency] :
            $currency;
        $currency = empty($currency) ? config('prices.currency.default') : $currency;
        $date     = str_starts_with($this->config["date"], "_") ?
            ($attributes[$key . $this->config["date"]] ?? Carbon::now()) :
            $this->config["date"];
        $date     = (is_string($date) || is_int($date)) && key_exists($date, $attributes) ?
            $attributes[$date] : $date;
        $date     = is_string($date) && $model->isFillable($date)
            ? ($attributes[$date] ?? Carbon::now())
            : (is_string($date)
                ? Carbon::createFromFormat('Y-m-d', preg_replace('/\s[\d\:\.]+/', '', $date))
                : $date
            );

        $factory = $factory->setCurrency($currency)
            ->setClassification(app(Creator::class)->createFromString($category ?? ''))
            ->setCountry($country)
            ->setDate($date)
            ->setValue(
                $this->config["inMinor"] !== 'false' ?
                    $value / (10 ** (Currency::get($currency)->getMinorUnitRate() ?? 0)) :
                    $value
            )
            ->setIsVATIncluded($this->config["vat"] === 'true');

        if ($this->config["any"] === 'true') {
            $factory->setAnyVAT();
        }

        return $factory->create();
    }

    /**
     * Sets the Price attribute to be stored within a database.
     *
     * @param \Illuminate\Database\Eloquent\Model $model The model containing the price attribute.
     * @param string $key Key of the price.
     * @param float|\MiBo\Prices\Price $value Value of the price.
     * @param array<string, mixed> $attributes All attributes of the Model.
     *
     * @return array<string, mixed>|float
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): array|float
    {
        if (!$value instanceof Price) {
            return $value;
        }

        $value  = clone $value;
        $result = [];

        $currencyIsFillable = str_starts_with($this->config['currency'], '_')
            && $model->isFillable($key . $this->config['currency']);

        if ($currencyIsFillable && empty($attributes[$key . $this->config['currency']])) {
            $result[$key . $this->config['currency']] = $value->getUnit()->getAlphabeticalCode();
        } else if (!$currencyIsFillable) {
            $castingCurrency = !empty($this->config['currency'])
                && !str_starts_with($this->config['currency'], '_')
                ? $this->config['currency']
                : config('prices.currency.default');

            $value->convertToUnit(Currency::get($castingCurrency));
        }

        $countryFillable = str_starts_with($this->config['country'], '_')
            && $model->isFillable($key . $this->config['country']);

        if ($countryFillable && empty($attributes[$key . $this->config['country']])) {
            $result[$key . $this->config['country']] = $value->getVAT()->getCountryCode();
        } else {
            $castingCountry = !empty($this->config['country'])
                ? $this->config['country'] : config('prices.vat.country');

            !str_starts_with($castingCountry, '_') && $value->forCountry($castingCountry);
        }

        $categoryFillable = str_starts_with($this->config['category'], '_')
            && $model->isFillable($key . $this->config['category']);

        if ($categoryFillable && empty($attributes[$key . $this->config['category']])) {
            $result[$key . $this->config['category']] = $value->getVAT()->getClassification()->getCode();
        } else if (!$categoryFillable
            && $this->config['any'] !== 'true'
            && ($this->config['category'] !== $value->getVAT()->getClassification()->getCode()
            && (self::$categoryCallback === null
            || !(self::$categoryCallback)(false, $model, $attributes, $key, $value)))
        ) {
            throw new ValueError(
                strtr(
                    'Tried to save a Price with category :cat which does not match the set category (:set)!',
                    [
                        ':cat' => $value->getVAT()->getClassification()->getCode(),
                        ':set' => $this->config['category'] ?? '',
                    ]
                )
            );
        }

        $dateFillable = str_starts_with($this->config['date'], '_')
            && $model->isFillable($key . $this->config['date']);

        if ($dateFillable && empty($attributes[$key . $this->config['date']])) {
            $result[$key . $this->config['date']] = $value->getDateTime();
        }

        if ($this->config['positive'] === 'true' && $value->isNegative()) {
            throw new NegativePriceException(
                strtr(
                    'Price to be saved must be positive or equal to zero! :value given.',
                    [':value' => $value->getValue()]
                )
            );
        }

        if ($this->config["vat"] === 'true') {
            $result[$key] = $this->config["inMinor"] === 'true' ?
                $value->getValueWithVAT() * (10 ** ($value->getUnit()->getMinorUnitRate() ?? 0)) :
                $value->getValueWithVAT();
        } else {
            $result[$key] = $this->config["inMinor"] === 'true' ?
                $value->getValue() * (10 ** ($value->getUnit()->getMinorUnitRate() ?? 0)) :
                $value->getValue();
        }

        return $result;
    }

    /**
     * @param (\Closure(bool, \Illuminate\Database\Eloquent\Model, array, string, mixed): mixed)|null $closure
     *
     * @return void
     */
    public static function setCategoryCallback(?Closure $closure): void
    {
        self::$categoryCallback = $closure;
    }
}
