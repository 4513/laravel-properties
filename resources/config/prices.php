<?php

declare(strict_types=1);

return [
    'printer'    => \MiBo\Properties\Printers\Printer::class,
    'vat'        => [
        'resolver'  => \MiBo\VAT\Resolvers\NullResolver::class,
        'convertor' => null,
        'country'   => 'US',
    ],
    'calculator' => null,
    'convertor'  => \MiBo\Currency\Rates\Exchangers\ECB::class,
    'comparer'   => null,
    'currency'   => [
        'loader'  => \MiBo\Currencies\ISO\ISOArrayListLoader::class,
        'default' => 'USD',
    ],
];
