<?php

declare(strict_types=1);

namespace MiBo\Prices\Tests\Coverage\Providers;

use Generator;

/**
 * Class NumericPropCastingProvider
 *
 * @package MiBo\Prices\Tests\Coverage\Providers
 *
 * @author 9I214
 * @author 6I012
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
class NumericPropCastingProvider
{
    public static function provideRawValue(): Generator
    {
        $list = [];

        // 100 % positive values.
        for ($i = 0; $i < 10; $i++) {
            $value = rand(1, 1_000_000);

            if (in_array($value, $list)) {
                continue;
            }

            $list[] = $value;

            yield "Positive integer ($value)" => [$value];
        }

        $list = [];

        // 100 % negative values.
        for ($i = 0; $i < 10; $i++) {
            $value = rand(-1_000_000, -1);

            if (in_array($value, $list)) {
                continue;
            }

            $list[] = $value;

            yield "Negative integer ($value)" => [$value];
        }

        yield 'Zero' => [0];

        $list = [];

        // 100 % positive values.
        for ($i = 0; $i < 10; $i++) {
            $value = rand(1, 1_000_000);

            if (in_array($value, $list)) {
                continue;
            }

            $list[] = $value;

            yield "Negative float ($value / 100)" => [$value / 100];
        }

        $list = [];

        // 100 % negative values.
        for ($i = 0; $i < 10; $i++) {
            $value = rand(-1_000_000, -1);

            if (in_array($value, $list)) {
                continue;
            }

            $list[] = $value;

            yield "Negative float ($value / 100)" => [$value / 100];
        }

        $list = [];

        // Random numbers.
        for ($i = 0; $i < 10; $i++) {
            $value  = rand(-999_999_999, 999_999_999);
            $value2 = rand(1, 15);
            $value  = $value2 % 2 === 0 ? round($value / 100, 5) : $value;

            if (in_array($value, $list)) {
                continue;
            }

            $list[] = $value;

            yield "Random value ($value)" => [$value];
        }
    }
}
