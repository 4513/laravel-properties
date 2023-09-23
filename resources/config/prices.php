<?php

declare(strict_types=1);

return [
    'defaults' => [
        'currency'        => 'USD',
        'country'         => 'US',
        'printer'         => \MiBo\Prices\Printers\Printer::class,
        'vat_resolver'    => \MiBo\VAT\Resolvers\NullResolver::class,
        'price_convertor' => null,
    ],
];
