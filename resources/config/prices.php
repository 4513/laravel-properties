<?php

declare(strict_types=1);

return [
    'printer'   => \MiBo\Prices\Printers\Printer::class,
    'vat'       => [
        'resolver'  => \MiBo\VAT\Resolvers\NullResolver::class,
        'convertor' => null,
        'country'   => 'US',
    ],
    'convertor' => null,
    'currency'  => [
        'loader'  => \MiBo\Currencies\ISO\ISOArrayListLoader::class,
        'default' => 'USD',
    ],
];
