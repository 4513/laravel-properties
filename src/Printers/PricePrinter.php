<?php

declare(strict_types=1);

namespace MiBo\Properties\Printers;

use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\Pure;
use MiBo\Prices\Contracts\PriceInterface;
use MiBo\Prices\Units\Price\Currency;
use MiBo\Properties\Contracts\NumericalProperty;
use MiBo\Properties\Contracts\SubjectToTax;

/**
 * Class PricePrinter
 *
 *  This Printer is used for printing prices. It retrieves current user from Auth
 * Laravel Facade to determine whether the printer price should contain VAT or not.
 * Furthermore, it uses current locale file to determine the correct currency for
 * the user, because the currency might be changed while the application run some
 * calculations on the price. This behaviour (changing the currency) can be disabled
 * by setting the Printer's public property $convertCurrencyByLocale to false.
 *
 *  If the Printer fails to retrieve the current user, the value that is used to
 * determine whether the price should contain VAT or not is taken from the config
 * file, under prices.vat.visitor_is_payer key. If the key is not set, the default
 * value of that key is 'false', which means that the price will be printed with
 * value added tax.
 *
 * @package MiBo\Properties\Printers
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
class PricePrinter extends Printer
{
    public static bool $convertCurrencyByLocale = true;

    /**
     * @inheritDoc
     */
    #[Pure]
    public function printProperty(NumericalProperty $property, ?int $decimals = null): string
    {
        if (!$property instanceof PriceInterface) {
            return parent::printProperty($property, $decimals);
        }

        if (self::$convertCurrencyByLocale === true) {
            $property->convertToUnit(Currency::get(trans($this->getTransKey($property, 'default'))));
        }

        $user     = Auth::getUser();
        $vatPayer = $user instanceof SubjectToTax ? $user->paysVAT() : (bool) config(
            'prices.vat.visitor_is_payer',
            false
        );
        $value    = $vatPayer ? $property->getValue() : $property->getValueWithVAT();

        return $this->print(
            (string) $value,
            trans_choice($this->getTransKey(
                $property,
                'units',
                // @phpstan-ignore-next-line
                $property->getUnit()->getAlphabeticalCode(),
                'symbol'
            ), (int) $value),
            $decimals ?? ($property->getUnit()->getMinorUnitRate() ?? 0)
        );
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function print(string $value, string $unit, ?int $decimals = null): string
    {
        $isPositive = (float) $value === abs((float) $value);
        $value      = abs((float) $value);
        $format     = $this->getNumberFormat();
        $integer    = sprintf('%d', $value);
        $integer    = str_repeat(
                '0',
                $format['grouping'][0]
                    ? $format['grouping'][0] - (strlen($integer) % $format['grouping'][0])
                    : 0
            ) . $integer;
        $decimals   = match ($decimals) {
            0, null => null,
            default => preg_replace(
                '/\d+\./',
                '',
                sprintf('%0.' . $decimals . 'F', $value)
            ),
        };

        return trim(
            trans(
                'properties::price.format.short',
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
                                ? rtrim($format['decimal_point'] . implode(
                                    $format['thousand_separator'],
                                    str_split($decimals, $format['grouping'][1] ?: PHP_INT_MAX)
                                ), $format['decimal_point'] . ' ')
                                : '',
                        ]
                    ),
                ]
            )
        );
    }
}
