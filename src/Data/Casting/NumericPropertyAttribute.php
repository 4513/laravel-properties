<?php

declare(strict_types=1);

namespace MiBo\Prices\Data\Casting;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use MiBo\Properties\Contracts\NumericalUnit;
use MiBo\Properties\NumericalProperty;
use ValueError;

/**
 * Class NumericPropertyAttribute
 *
 * *The Attribute casts a numeric property and its unit from and to database.*
 *
 * Synopsys:
 * * \MiBo\Prices\Data\Casting\NumericPropertyAttribute::class . [':attr-value' . [',:attr-value' ...]]
 * * \MiBo\Prices\Data\Casting\NumericPropertyAttribute::class . [':class-string(property)']
 *
 * Example:
 * * \MiBo\Prices\Data\Casting\NumericPropertyAttribute::class . ':unit-(class-string)|(iso-ext)'
 *
 *  **Property** class that implements \MiBo\Properties\Contracts\NumericalProperty and is being used as a
 * result.
 * <ul><li>Setting
 *   <ul><li>Directly passed: <i>class-string(NumericalProperty)</i></li>
 *   <li>Passed as a parameter: <i>property-class-string(NumericalProperty)</i></li></ul>
 * If the property class is not directly specified, it is determined by the unit class.</li></ul>
 * **Unit** class that implements \MiBo\Properties\Contracts\NumericalUnit.
 * <ul><li>Setting
 *   <ul><li>Passed as a parameter: <i>unit-class-string(NumericalUnit)</i></li>
 *   <li>Specified as an ISO ext (milli, micro,...): <i>unit-DECI, unit-KILO,...</i></li></ul>
 * If the unit class is not specified, default unit of the given property is used.</li></ul>
 *
 * @package MiBo\Prices\Data\Casting
 *
 * @author 9I214
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
class NumericPropertyAttribute implements CastsAttributes
{
    private const DEFAULTS = [
        'unit'     => null,
        'property' => null,
    ];

    /**
     * @var array{
     *     unit: string|class-string<\MiBo\Properties\Contracts\NumericalUnit>|null,
     *     property: class-string<\MiBo\Properties\Contracts\NumericalProperty>|null
     * }
     */
    private array $config;

    public function __construct(string ...$segments)
    {
        foreach ($segments as $key => $segment) {
            $arguments               = explode('-', $segment, 2);
            $segments[$arguments[0]] = $arguments[1] ?? null;
            unset($segments[$key]);
        }

        if (count($segments) === 1 && $segments[array_keys($segments)[0]] === null) {
            $segments = ["property" => array_keys($segments)[0]];
        }

        $this->config = array_merge(self::DEFAULTS, $segments);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param float|int|\MiBo\Properties\NumericalProperty $value
     * @param array $attributes
     *
     * @return \MiBo\Properties\NumericalProperty
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): NumericalProperty
    {
        if ($value instanceof NumericalProperty) {
            return $value;
        }

        if (($this->config["unit"] === null || !class_exists($this->config["unit"]))
            && $this->config["property"] === null
        ) {
            throw new ValueError("To cast a numeric property, either unit or property must be specified!");
        }

        $isoExt = false;

        /** @var class-string<\MiBo\Properties\NumericalProperty> $propertyClass */
        $propertyClass = $this->config["property"] ??
            $this->config["unit"]::getQuantityClassName()::getDefaultProperty();

        $unit = $this->config["unit"] ?? $propertyClass::getQuantityClassName()::getDefaultUnit();

        if (!$unit instanceof NumericalUnit) {
            $unit   = class_exists($unit) ? $unit::get() : $unit;
            $isoExt = in_array($unit, [
                "ATTO",
                "CENTI",
                "DECA",
                "DECI",
                "EXA",
                "FEMTO",
                "GIGA",
                "HECTO",
                "KILO",
                "MEGA",
                "MICRO",
                "MILLI",
                "NANO",
                "PETA",
                "PICO",
                "TERA",
                "YOCTO",
                "YOTTA",
                "ZEPTO",
                "ZETTA",
            ]) ? $unit : false;
            unset($unit);
        }

        if ($isoExt !== false) {
            return $propertyClass::$isoExt($value);
        }

        return new ($propertyClass)($value, $unit ?? $propertyClass::getQuantityClassName()::getDefaultUnit());
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param float|int|\MiBo\Properties\NumericalProperty $value
     * @param array $attributes
     *
     * @return int|float
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): int|float
    {
        if (!$value instanceof NumericalProperty) {
            return $value;
        }

        if (($this->config["unit"] === null || !class_exists($this->config["unit"]))
            && $this->config["property"] === null
        ) {
            throw new ValueError("To cast a numeric property, either unit or property must be specified!");
        }

        $unit   = $this->config["unit"];
        $isoExt = in_array($unit, [
            "ATTO",
            "CENTI",
            "DECA",
            "DECI",
            "EXA",
            "FEMTO",
            "GIGA",
            "HECTO",
            "KILO",
            "MEGA",
            "MICRO",
            "MILLI",
            "NANO",
            "PETA",
            "PICO",
            "TERA",
            "YOCTO",
            "YOTTA",
            "ZEPTO",
            "ZETTA",
        ]) ? $unit : false;
        $unit   = $unit ?? $value->getUnit();
        $unit   = $isoExt === false ? $unit :
            $value::$unit(0)->getUnit();
        $value->convertToUnit($unit);

        return $value->getValue();
    }
}
