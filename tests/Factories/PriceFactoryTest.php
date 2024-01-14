<?php

declare(strict_types=1);

namespace MiBo\Properties\Tests\Factories;

use Closure;
use Exception;
use MiBo\Properties\Classifications\Creator;
use MiBo\Properties\Data\Factories\PriceFactory;
use MiBo\Properties\Tests\LaravelTestCase;

/**
 * Class PriceFactoryTest
 *
 * @package MiBo\Properties\Tests\Factories
 *
 * @author 3I666
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
class PriceFactoryTest extends LaravelTestCase
{
    /**
     * @small
     *
     * @coversNothing
     *
     * @param \Closure(): \MiBo\Prices\Price $createExpectedPrice
     * @param float|int $value
     * @param array{
     *     currency?: non-empty-string,
     *     category?: string,
     *     country?: string,
     *     date?: \DateTimeInterface,
     *     isVATIncluded?: bool,
     *     isAnyVat?: bool,
     *     strictPositive?: true|null,
     * } $data
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\PriceProvider::dataForCreatingPrices()
     */
    public function testCreatingPrice(
        Closure $createExpectedPrice,
        float|int $value,
        array $data
    ): void
    {
        $price   = $createExpectedPrice();
        $factory = PriceFactory::get();

        $factory->setValue($value);
        $factory->setClassification(app(Creator::class)->createFromString(''));

        foreach ($data as $key => $value) {
            if ($value === null) {
                continue;
            }

            switch ($key) {
                case 'currency':
                    $factory->setCurrency($value);
                break;

                case 'category':
                    $factory->setClassification(app(Creator::class)->createFromString($value));
                break;

                case 'country':
                    $factory->setCountry($value);
                break;

                case 'date':
                    $factory->setDate($value);
                break;

                case 'isVATIncluded':
                    if ($value) {
                        $factory->isWithVAT();
                    } else {
                        $factory->isWithoutVAT();
                    }
                break;

                case 'isAnyVAT':
                    $factory->setAnyVAT();
                break;

                case 'strictPositive':
                    $factory->strictlyPositive();
                break;

                default:
                throw new Exception("Unknown key: {$key}");
            }
        }

        for ($i = 0; $i < 10; $i++) {
            $createdPrice = $factory->create();

            $this->assertSame($price->getValue(), $createdPrice->getValue());
            $this->assertTrue($createdPrice instanceof $price);
            $this->assertTrue($price->getUnit()->is($createdPrice->getUnit()));
            $this->assertTrue($price->getVAT()->is($createdPrice->getVAT()));
            $this->assertSame(
                $price->getDateTime()?->format('Y-m-d'),
                $createdPrice->getDateTime()?->format('Y-m-d')
            );
            $this->assertSame($price->getValueWithVAT(), $createdPrice->getValueWithVAT());
        }
    }
}
