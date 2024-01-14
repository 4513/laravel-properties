<?php

declare(strict_types=1);

return [
    'printer'    => \MiBo\Properties\Printers\PricePrinter::class,
    'vat'        => [
        'value_resolver'   => \MiBo\VAT\ValueResolvers\EUValueResolver::class,
        'resolver'         => null,
        'convertor'        => null,
        'country'          => 'US',
        'visitor_is_payer' => false,
        'classification_creator' => \MiBo\Properties\Classifications\Creator::class,
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
