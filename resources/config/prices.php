<?php

declare(strict_types=1);

return [
    'printer'    => \MiBo\Properties\Printers\PricePrinter::class,
    'vat'        => [
        'resolver'         => \MiBo\VAT\Resolvers\NullResolver::class,
        'convertor'        => null,
        'country'          => 'US',
        'visitor_is_payer' => false,
    ],
    'calculator' => null,
    'convertor'  => \MiBo\Currency\Rates\Exchangers\ECB::class,
    'comparer'   => null,
    'currency'   => [
        'loader'   => \MiBo\Currencies\ISO\ISOArrayListLoader::class,
        'provider' => \MiBo\Currencies\ISO\ISOCurrencyProvider::class,
        'default'  => 'USD',
    ],
];
