<?php

declare(strict_types=1);

namespace MiBo\Properties\Printers;

use Illuminate\Support\Facades\App;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use MiBo\Prices\Contracts\PriceInterface;
use MiBo\Properties\Contracts\NumericalProperty;
use MiBo\Properties\Contracts\PrinterInterface;
use MiBo\Properties\Units\ThermodynamicTemperature\DegreeCelsius;
use function is_float;
use function strlen;

/**
 * Class Printer
 *
 * @package MiBo\Prices\Printers
 *
 * @author 9I214
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
class Printer implements PrinterInterface
{
    /**
     * @inheritDoc
     *
     * @phpcs:ignore Generic.Files.LineLength.TooLong
     * @param \MiBo\Properties\Contracts\NumericalProperty&\MiBo\Properties\Contracts\NumericalComparableProperty $property
     * @param int<0, max>|null $decimals Number of decimal places to be shown. If null (default) the property
     *     is printed without checking the number of decimal places.
     */
    #[Pure]
    public function printProperty(NumericalProperty $property, ?int $decimals = null): string
    {
        $isPositive = $property->isNotNegative();
        $value      = abs($property->getValue());
        $format     = $this->getNumberFormat();
        $integer    = sprintf('%d', $value);
        $integer    = str_repeat(
                '0',
                $format['grouping'][0]
                    ? $format['grouping'][0] - (strlen($integer) % $format['grouping'][0])
                    : 0
            ) . $integer;
        $decimals   = match ($decimals) {
            0       => null,
            null    => is_float($value)
                ? preg_replace('/\d+\./', '', (string) $value)
                : null,
            default => preg_replace(
                '/\d+\./',
                '',
                sprintf('%0.' . $decimals . 'F', $value)
            ),
        };

        $data = [
            'symbol' => $isPositive ? $format['positive_sign'] : $format["negative_sign"],
            'unit'   => $property instanceof PriceInterface
                ? trans_choice($this->getTransKey(
                    $property,
                    'units',
                    // @phpstan-ignore-next-line
                    $property->getUnit()->getAlphabeticalCode(),
                    'symbol'
                ), (int) $value)
                : trans_choice(
                    $this->getTransKey($property, 'units', $property->getUnit()->getName(), 'symbol'),
                    (int) $value
                ),
            'count'  => strtr(
                ':int:decimal',
                [
                    ':int'     => (ltrim(
                        implode(
                            $format['thousand_separator'],
                            str_split($integer, $format['grouping'][0] ?: PHP_INT_MAX)
                        ),
                        '0' . $format['thousand_separator']
                    )) ?: '0',
                    ':decimal' => $decimals !== null
                        ? $format['decimal_point'] . implode(
                            $format['thousand_separator'],
                            str_split($decimals, $format['grouping'][1] ?: PHP_INT_MAX)
                        )
                        : '',
                ]
            ),
        ];

        return $property->getUnit()->is(DegreeCelsius::get())
            ? strtr('symbolcountunit', $data)
            // @phpstan-ignore-next-line
            : trim(trans($this->getTransKey($property, 'format.short'), $data));
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function print(string $value, string $unit, ?int $decimals = null): string
    {
        $isPositive = (float) $value === abs((float) $value);
        $value      = abs((float) $value);
        $stringTpl  = $unit === '°C' ? 'symbolcountunit' : 'symbolcount unit';
        $format     = $this->getNumberFormat();
        $integer    = sprintf('%d', $value);
        $integer    = str_repeat(
                '0',
                $format['grouping'][0]
                    ? $format['grouping'][0] - (strlen($integer) % $format['grouping'][0])
                    : 0
            ) . $integer;
        $decimals   = match ($decimals) {
            0       => null,
            default => preg_replace(
                '/\d+\./',
                '',
                sprintf('%0.' . $decimals . 'F', $value)
            ),
        };

        return trim(strtr(
            $stringTpl,
            [
                'symbol' => $isPositive ? $format['positive_sign'] : $format["negative_sign"],
                'unit'   => $unit,
                'count'  => strtr(
                    ':int:decimal',
                    [
                        ':int'     => (ltrim(
                            implode(
                                $format['thousand_separator'],
                                str_split($integer, $format['grouping'][0] ?: PHP_INT_MAX)
                            ),
                            '0' . $format['thousand_separator']
                        )) ?: '0',
                        ':decimal' => $decimals !== null
                            ? $format['decimal_point'] . implode(
                                $format['thousand_separator'],
                                str_split($decimals, $format['grouping'][1] ?: PHP_INT_MAX)
                            )
                            : '',
                    ]
                ),
            ]
        ));
    }

    /**
     * @return array{
     *     decimal_point: non-empty-string,
     *     thousand_separator: string,
     *     positive_sign: string,
     *     negative_sign: non-empty-string,
     *     grouping: array{0: int<0, 1>, 1: positive-int|null}
     * }
     */
    #[Pure]
    #[ArrayShape([
        'decimal_point'      => 'string',
        'thousand_separator' => 'string',
        'positive_sign'      => 'string',
        'negative_sign'      => 'string',
        'grouping'           => 'array{0: int<0,1>, 1: positive-int|null}',
    ])]
    final protected function getNumberFormat(): array
    {
        static $format = [];

        $locale = App::getLocale();

        if (key_exists($locale, $format)) {
            return $format[$locale];
        }

        /** @var numeric-string|null $decimalGrouping */
        $decimalGrouping = trans('properties::properties.number.format.grouping.dec');
        $format[$locale] = [
            'decimal_point'      => trans('properties::properties.number.format.decimal pt') ?? '.',
            'thousand_separator' => trans('properties::properties.number.format.thousand sep') ?? ',',
            'positive_sign'      => trans('properties::properties.number.format.pos sign') ?? '',
            'negative_sign'      => trans('properties::properties.number.format.neg sign') ?? '-',
            'grouping'           => [
                // @phpstan-ignore-next-line
                (int) (trans('properties::properties.number.format.grouping.int') ?? 3),
                $decimalGrouping === null ? null : (int) $decimalGrouping,
            ],
        ];

        /**
         * @phpstan-var array{
         *     decimal_point: non-empty-string,
         *     thousand_separator: string,
         *     positive_sign: string,
         *     negative_sign: non-empty-string,
         *     grouping: array{0: int<0, 1>, 1: positive-int|null}
         * }
         */
        return $format[$locale];
    }

    /**
     * @param \MiBo\Properties\Contracts\NumericalProperty $property
     * @param string ...$key
     *
     * @return non-empty-string
     */
    #[Pure]
    final protected function getTransKey(NumericalProperty $property, string ...$key): string
    {
        return 'properties::' .
            // @phpstan-ignore-next-line
            strtolower($property::getQuantityClassName()::getNameForTranslation()) .
            '.' .
            implode('.', $key);
    }
}
