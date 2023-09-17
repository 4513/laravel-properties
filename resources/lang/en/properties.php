<?php

declare(strict_types=1);

return [
    'number' => [
        'format' => [
            // Decimal point character (e.g. '.' in 1,234,456.78).
            'decimal pt'   => '.',
            // Thousands separator (e.g. ',' in 1,234,456.78).
            'thousand sep' => ',',
            // Positive sign character (e.g. '+' in +1,234,456.78).
            'pos sign'     => '',
            // Negative sign character (e.g. '-' in -1,234,456.78).
            'neg sign'     => '-',
            //  Grouping of the numbers. The value is an array. First value is the number of digits in the
            // groups of the integer part of the number. Second value is the number of digits in the groups
            // in the decimal part of the number. If the grouping is [3, 2], then the number 123456.7809 is
            // shown as 123,456.78,89.
            'grouping'     => [
                'int' => '3',
                'dec' => '3',
            ],
        ],
    ],
];
