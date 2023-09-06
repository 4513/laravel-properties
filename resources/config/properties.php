<?php

declare(strict_types=1);

return [
    'allowedQuantities' => [
        \MiBo\Properties\Quantities\AmountOfSubstance::class,
        \MiBo\Properties\Quantities\Area::class,
        \MiBo\Properties\Quantities\ElectricCurrent::class,
        \MiBo\Properties\Quantities\Length::class,
        \MiBo\Properties\Quantities\LuminousIntensity::class,
        \MiBo\Properties\Quantities\Mass::class,
        \MiBo\Properties\Quantities\Pure::class,
        \MiBo\Properties\Quantities\ThermodynamicTemperature::class,
        \MiBo\Properties\Quantities\Time::class,
        \MiBo\Properties\Quantities\Volume::class,
        \MiBo\Prices\Quantities\Price::class,
    ],
    'defaultUnits'      => [
        'class-string<\MiBo\Properties\Contracts\Quantity>'  => 'class-string<\MiBo\Properties\Contracts\Unit>',
        'class-string<\MiBo\Properties\Contracts\Quantity>2' => 'class-string<\MiBo\Properties\Contracts\Unit>',
    ],
];
