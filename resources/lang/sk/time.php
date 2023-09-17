<?php

declare(strict_types=1);

return [
    'name'   => 'Čas',
    'units'  => [
        'second'      => [
            'name'       => '{1} sekunda|[2,4] sekundy|[5,*] sekúnd',
            'name-float' => 'sekundy',
            'symbol'     => 's',
        ],
        'attosecond'  => [
            'name'       => '{1} attosekunda|[2,4] sekundy|[5,*] attosekúnd',
            'name-float' => 'attosekundy',
            'symbol'     => 'as',
        ],
        'centisecond' => [
            'name'       => '{1} centisekunda|[2,4] centisekundy|[5,*] centisekúnd',
            'name-float' => 'centisekundy',
            'symbol'     => 'cs',
        ],
        'decasecond'  => [
            'name'       => '{1} dekasekunda|[2,4] dekasekundy|[5,*] dekasekúnd',
            'name-float' => 'dekasekundy',
            'symbol'     => 'das',
        ],
        'decisecond'  => [
            'name'       => '{1} decisekunda|[2,4] decisekundy|[5,*] decisekúnd',
            'name-float' => 'decisekundy',
            'symbol'     => 'ds',
        ],
        'exasecond'   => [
            'name'       => '{1} exasekunda|[2,4] exasekundy|[5,*] exasekúnd',
            'name-float' => 'exasekundy',
            'symbol'     => 'Es',
        ],
        'femtosecond' => [
            'name'       => '{1} femtosekunda|[2,4] femtosekundy|[5,*] femtosekúnd',
            'name-float' => 'femtosekundy',
            'symbol'     => 'fs',
        ],
        'gigasecond'  => [
            'name'       => '{1} gigasekunda|[2,4] gigasekundy|[5,*] gigasekúnd',
            'name-float' => 'gigasekundy',
            'symbol'     => 'Gs',
        ],
        'hectosecond' => [
            'name'       => '{1} hektosekunda|[2,4] hektosekundy|[5,*] hektosekúnd',
            'name-float' => 'hektosekundy',
            'symbol'     => 'hs',
        ],
        'kilosecond'  => [
            'name'       => '{1} kilosekunda|[2,4] kilosekundy|[5,*] kilosekúnd',
            'name-float' => 'kilosekundy',
            'symbol'     => 'ks',
        ],
        'megasecond'  => [
            'name'       => '{1} megasekunda|[2,4] megasekundy|[5,*] megasekúnd',
            'name-float' => 'megasekundy',
            'symbol'     => 'Ms',
        ],
        'microsecond' => [
            'name'       => '{1} mikrosekunda|[2,4] mikrosekundy|[5,*] mikrosekúnd',
            'name-float' => 'mikrosekundy',
            'symbol'     => 'μs',
        ],
        'millisecond' => [
            'name'       => '{1} milisekunda|[2,4] milisekundy|[5,*] milisekúnd',
            'name-float' => 'milisekundy',
            'symbol'     => 'ms',
        ],
        'nanosecond'  => [
            'name'       => '{1} nanosekunda|[2,4] nanosekundy|[5,*] nanosekúnd',
            'name-float' => 'nanosekundy',
            'symbol'     => 'ns',
        ],
        'petasecond'  => [
            'name'       => '{1} petasekunda|[2,4] petasekundy|[5,*] petasekúnd',
            'name-float' => 'petasekundy',
            'symbol'     => 'Ps',
        ],
        'picosecond'  => [
            'name'       => '{1} pikosekunda|[2,4] pikosekundy|[5,*] pikosekúnd',
            'name-float' => 'pikosekundy',
            'symbol'     => 'ps',
        ],
        'terasecond'  => [
            'name'       => '{1} terasekunda|[2,4] terasekundy|[5,*] terasekúnd',
            'name-float' => 'terasekundy',
            'symbol'     => 'Ts',
        ],
        'yoctosecond' => [
            'name'       => '{1} yoktosekunda|[2,4] yoktosekundy|[5,*] yoktosekúnd',
            'name-float' => 'yoktosekundy',
            'symbol'     => 'ys',
        ],
        'yottasecond' => [
            'name'       => '{1} yottasekunda|[2,4] yottasekundy|[5,*] yottasekúnd',
            'name-float' => 'yottasekundy',
            'symbol'     => 'Ys',
        ],
        'zeptosecond' => [
            'name'       => '{1} zeptosekunda|[2,4] zeptosekundy|[5,*] zeptosekúnd',
            'name-float' => 'zeptosekundy',
            'symbol'     => 'zs',
        ],
        'zettasecond' => [
            'name'       => '{1} zettasekunda|[2,4] zettasekundy|[5,*] zettasekúnd',
            'name-float' => 'zettasekundy',
            'symbol'     => 'Zs',
        ],
        'day'         => [
            'name'       => '{1} deň|[2,4] dni|[5,*] dní',
            'name-float' => 'dňa',
            'symbol'     => 'd',
        ],
        'hour'        => [
            'name'       => '{1} hodina|[2,4] hodiny|[5,*] hodín',
            'name-float' => 'hodiny',
            'symbol'     => 'h',
        ],
        'minute'      => [
            'name'       => '{1} minúta|[2,4] minúty|[5,*] minút',
            'name-float' => 'minúty',
            'symbol'     => 'min',
        ],
    ],
    'format' => [
        'short' => ':symbol:count :unit',
        'long'  => ':symbol:count :unit',
    ],
];
