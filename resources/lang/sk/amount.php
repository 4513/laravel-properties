<?php

declare(strict_types=1);

return [
    'name'   => 'Množstvo',
    'units'  => [
        'century' => [
            'name'       => '{1} stovka|[2,4] stovky|[5,*] stoviek',
            'name-float' => 'stovky',
            'symbol'     => 'st',
        ],
        'couple'  => [
            'name'       => '{1} dvojica|[2,4] dvojice|[5,*] dvojíc',
            'name-float' => 'dvojice',
            'symbol'     => null,
        ],
        'decade'  => [
            'name'       => '{1} desiatka|[2,4] desiatky|[5,*] desiatok',
            'name-float' => 'desiatky',
            'symbol'     => null,
        ],
        'dozen'   => [
            'name'       => '{1} tucen|[2,4] tucty|[5,*] tucnov',
            'name-float' => 'tucta',
            'symbol'     => null,
        ],
        'duo'     => [
            'name'       => '{1} duo|[2,4] duá|[5,*] duí',
            'name-float' => 'dua',
            'symbol'     => null,
        ],
        'pair'    => [
            'name'       => '{1} pár|[2,4] páry|[5,*] párov',
            'name-float' => 'páru',
            'symbol'     => null,
        ],
        'piece'   => [
            'name'       => '{1} kus|[2,4] kusy|[5,*] kusov',
            'name-float' => 'kusu',
            'symbol'     => 'ks',
        ],
        'quartet' => [
            'name'       => '{1} quartet|[2,4] quartetá|[5,*] quartetov',
            'name-float' => 'quarteta',
            'symbol'     => null,
        ],
        'trio'    => [
            'name'       => '{1} trio|[2,4] triá|[5,*] trií',
            'name-float' => 'tria',
            'symbol'     => null,
        ],
        'unit'    => [
            'name'       => '{1} jednotka|[2,4] jednotky|[5,*] jednotiek',
            'name-float' => 'jednotky',
            'symbol'     => '',
        ],
    ],
    'format' => [
        'short' => ':count :unit',
        'long'  => ':count :unit',
    ],
];
