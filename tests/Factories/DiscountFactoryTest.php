<?php

declare(strict_types=1);

namespace MiBo\Prices\Tests\Factories;

use Closure;
use MiBo\Prices\Contracts\Discountable;
use MiBo\Prices\Contracts\PriceInterface;
use MiBo\Prices\Data\Factories\DiscountFactory;
use MiBo\Prices\Data\Factories\PriceFactory;
use MiBo\Prices\Exceptions\CouldNotApplyWholeAmountOfDiscountException;
use MiBo\Prices\PositivePrice;
use MiBo\Prices\PositivePriceWithVAT;
use MiBo\Prices\Tests\LaravelTestCase;
use MiBo\VAT\Enums\VATRate;
use stdClass;
use ValueError;

/**
 * Class DiscountFactoryTest
 *
 * @package MiBo\Prices\Tests\Factories
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
class DiscountFactoryTest extends LaravelTestCase
{
    /**
     * @small
     *
     * @coversNothing
     *
     * @return void
     */
    public function testDefault(): void
    {
        $discount = DiscountFactory::get()
            ->create();

        $this->assertInstanceOf(PositivePrice::class, $discount);
        $this->assertEquals(0, $discount->getValue());
    }

    /**
     * @small
     *
     * @coversNothing
     *
     * @param \Closure(): array<\MiBo\Prices\Contracts\Discountable> $createList
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceProvider::provideDiscountableList()
     */
    public function testDefaultWithList(Closure $createList): void
    {
        $list     = $createList();
        $discount = DiscountFactory::get()
            ->setOption(DiscountFactory::OPT_SUBJECT, $list)
            ->create();

        $this->assertInstanceOf(PositivePrice::class, $discount);
        $this->assertEquals(count($list) * 25, $discount->getValue());
    }

    /**
     * @small
     *
     * @coversNothing
     *
     * @param \Closure(): array<\MiBo\Prices\Contracts\Discountable> $createList
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceProvider::provideDiscountableList()
     */
    public function testDefaultWithListWithVAT(Closure $createList): void
    {
        $list     = $createList();
        $discount = DiscountFactory::get()
            ->setOption(DiscountFactory::OPT_SUBJECT, $list)
            ->setOption(DiscountFactory::OPT_IS_VALUE_WITH_VAT, true)
            ->create();

        $this->assertInstanceOf(PositivePriceWithVAT::class, $discount);
        $this->assertEquals(count($list) * 25, $discount->getValue());
    }

    /**
     * @small
     *
     * @coversNothing
     *
     * @param \Closure(): array<\MiBo\Prices\Contracts\Discountable> $createList
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceProvider::provideDiscountableList()
     */
    public function testDefaultWithListWithFilter(Closure $createList): void
    {
        $list     = $createList();
        $discount = DiscountFactory::get()
            ->setOption(DiscountFactory::OPT_SUBJECT, $list)
            ->setOption(DiscountFactory::OPT_FILTER, static fn() => false)
            ->create();

        $this->assertInstanceOf(PositivePrice::class, $discount);
        $this->assertEquals(0, $discount->getValue());
    }

    /**
     * @small
     *
     * @coversNothing
     *
     * @param \Closure(): array<\MiBo\Prices\Contracts\Discountable> $createList
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceProvider::provideDiscountableList()
     */
    public function testDefaultWithListWithRequiredWholeSum(Closure $createList): void
    {
        $list  = $createList();
        $count = count($list);

        if ($count < 4) {
            $this->expectException(CouldNotApplyWholeAmountOfDiscountException::class);
        }

        $discount = DiscountFactory::get()
            ->setOption(DiscountFactory::OPT_SUBJECT, $list)
            ->setOption(DiscountFactory::OPT_REQUIRES_WHOLE_SUM_TO_USE, true)
            ->setOption(DiscountFactory::OPT_VALUE, 100)
            ->create();

        $this->assertInstanceOf(PositivePrice::class, $discount);
        $this->assertEquals(100, $discount->getValue());
    }

    /**
     * @small
     *
     * @coversNothing
     *
     * @param \Closure(): array<\MiBo\Prices\Contracts\Discountable> $createList
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceProvider::provideDiscountableList()
     */
    public function testDefaultWithListWithValue(Closure $createList): void
    {
        $list     = $createList();
        $count    = count($list);
        $discount = DiscountFactory::get()
            ->setOption(DiscountFactory::OPT_SUBJECT, $list)
            ->setOption(DiscountFactory::OPT_VALUE, 100)
            ->create();

        $this->assertInstanceOf(PositivePrice::class, $discount);
        $this->assertEquals(min(100, $count * 25), $discount->getValue());
    }

    /**
     * @small
     *
     * @coversNothing
     *
     * @param \Closure(): array<\MiBo\Prices\Contracts\Discountable> $createList
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceProvider::provideDiscountableList()
     */
    public function testDefaultWithListWithVATNone(Closure $createList): void
    {
        $list     = $createList();
        $discount = DiscountFactory::get()
            ->setOption(DiscountFactory::OPT_SUBJECT, $list)
            ->setOption(DiscountFactory::OPT_VAT, VATRate::NONE)
            ->create();

        $this->assertInstanceOf(PositivePrice::class, $discount);
        $this->assertEquals(0, $discount->getValue());
    }

    /**
     * @small
     *
     * @coversNothing
     *
     * @param \Closure(): array<\MiBo\Prices\Contracts\Discountable> $createList
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceProvider::provideDiscountableList()
     */
    public function testDefaultWithListWithPercentage(Closure $createList): void
    {
        $list     = $createList();
        $discount = DiscountFactory::get()
            ->setOption(DiscountFactory::OPT_SUBJECT, $list)
            ->setOption(DiscountFactory::OPT_PERCENTAGE_VALUE, 10)
            ->setOption(DiscountFactory::OPT_TYPE, DiscountFactory::TYPE_PERCENTAGE)
            ->create();

        $this->assertInstanceOf(PositivePrice::class, $discount);
        $this->assertEquals(count($list) * 2.5, $discount->getValue());
    }

    /**
     * @small
     *
     * @coversNothing
     *
     * @param \Closure(): array<\MiBo\Prices\Contracts\Discountable> $createList
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceProvider::provideDiscountableList()
     */
    public function testDefaultWithListWithPercentageAndValue(Closure $createList): void
    {
        $list     = $createList();
        $discount = DiscountFactory::get()
            ->setOption(DiscountFactory::OPT_SUBJECT, $list)
            ->setOption(DiscountFactory::OPT_PERCENTAGE_VALUE, 10)
            ->setOption(DiscountFactory::OPT_TYPE, DiscountFactory::TYPE_PERCENTAGE)
            ->setOption(DiscountFactory::OPT_VALUE, 100)
            ->create();

        $this->assertInstanceOf(PositivePrice::class, $discount);
        $this->assertEquals(min(count($list) * 2.5, 100), $discount->getValue());
    }

    /**
     * @small
     *
     * @coversNothing
     *
     * @param \Closure(): array<\MiBo\Prices\Contracts\Discountable> $createList
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceProvider::provideDiscountableList()
     */
    public function testDefaultWithCombinedVAT(Closure $createList): void
    {
        $list = $createList();

        if (count($list) >= 2) {
            $list[0]->getPrice()->add($list[1]->getPrice());
        }

        $discount = DiscountFactory::get()
            ->setOption(DiscountFactory::OPT_SUBJECT, $list)
            ->setOption(DiscountFactory::OPT_VAT, VATRate::REDUCED)
            ->setOption(DiscountFactory::OPT_VALUE, 100)
            ->create();

        $this->assertInstanceOf(PositivePrice::class, $discount);
    }

    /**
     * @small
     *
     * @coversNothing
     *
     * @return void
     */
    public function testCombinedVATOutFiltered(): void
    {
        $price    = PriceFactory::get()
            ->setValue(10)
            ->setCategory('2201')
            ->setCountry('CZE')
            ->create()
            ->add(
                PriceFactory::get()
                    ->setValue(10)
                    ->setCategory('1')
                    ->setCountry('CZE')
                    ->create()
            )
            ->add(
                PriceFactory::get()
                    ->setValue(10)
                    ->setCategory('2201')
                    ->setCountry('CZE')
                    ->create()
            )
            ->add(
                PriceFactory::get()
                    ->setValue(10)
                    ->setCategory('06')
                    ->setCountry('CZE')
                    ->create()
            );
        $discount = DiscountFactory::get()
            ->setOption(DiscountFactory::OPT_COUNTRY, 'CZE')
            ->setOption(DiscountFactory::OPT_FILTER, static fn(): bool => false)
            ->setOption(DiscountFactory::OPT_VAT, VATRate::NONE)
            ->setOption(
                DiscountFactory::OPT_SUBJECT,
                [
                    new class ($price) implements Discountable {
                        private PriceInterface $price;

                        public function __construct(PriceInterface $price)
                        {
                            $this->price = $price;
                        }

                        public function registerDiscountPrice(PriceInterface $discount): void
                        {
                        }

                        public function getPrice(): PriceInterface
                        {
                            return $this->price;
                        }
                    },
                ]
            )
            ->create();

        $this->assertEquals(0, $discount->getValue());
    }

    /**
     * @small
     *
     * @coversNothing
     *
     * @return void
     */
    public function testCombinedVATFiltered(): void
    {
        $price    = PriceFactory::get()
            ->setValue(10)
            ->setCategory('2201')
            ->setCountry('CZE')
            ->create()
            ->add(
                PriceFactory::get()
                    ->setValue(10)
                    ->setCategory('1')
                    ->setCountry('CZE')
                    ->create()
            )
            ->add(
                PriceFactory::get()
                    ->setValue(10)
                    ->setCategory('2201')
                    ->setCountry('CZE')
                    ->create()
            )
            ->add(
                PriceFactory::get()
                    ->setValue(10)
                    ->setCategory('06')
                    ->setCountry('CZE')
                    ->create()
            );
        $discount = DiscountFactory::get()
            ->setOption(DiscountFactory::OPT_COUNTRY, 'CZE')
            ->setOption(DiscountFactory::OPT_FILTER, static fn(): bool => true)
            ->setOption(DiscountFactory::OPT_VAT, VATRate::NONE)
            ->setOption(
                DiscountFactory::OPT_SUBJECT,
                [
                    new class ($price) implements Discountable {
                        private PriceInterface $price;

                        public function __construct(PriceInterface $price)
                        {
                            $this->price = $price;
                        }

                        public function registerDiscountPrice(PriceInterface $discount): void
                        {
                        }

                        public function getPrice(): PriceInterface
                        {
                            return $this->price;
                        }
                    },
                ]
            )
            ->create();

        $this->assertEquals(10, $discount->getValue());
    }

    /**
     * @small
     *
     * @coversNothing
     *
     * @return void
     */
    public function testIncompatibleSubject(): void
    {
        $factory = DiscountFactory::get();

        $factory->setOption(DiscountFactory::OPT_SUBJECT, [new stdClass()]);
        $this->expectException(ValueError::class);
        $factory->create();
    }

    /**
     * @small
     *
     * @coversNothing
     *
     * @param \Closure(): array<\MiBo\Prices\Contracts\Discountable> $createList
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceProvider::provideDiscountableList()
     */
    public function testDefaultWithCombinedVATWithFilter(Closure $createList): void
    {
        $list = $createList();

        if (count($list) >= 2) {
            $list[0]->getPrice()->add($list[1]->getPrice());
        }

        $discount = DiscountFactory::get()
            ->setOption(DiscountFactory::OPT_SUBJECT, $list)
            ->setOption(DiscountFactory::OPT_VAT, VATRate::REDUCED)
            ->setOption(DiscountFactory::OPT_FILTER, fn() => false)
            ->setOption(DiscountFactory::OPT_VALUE, 100)
            ->create();

        $this->assertInstanceOf(PositivePrice::class, $discount);
        $this->assertEquals(0, $discount->getValue());
    }

    /**
     * @small
     *
     * @coversNothing
     *
     * @param \Closure(): array<\MiBo\Prices\Contracts\Discountable> $createList
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceProvider::provideDiscountableList()
     */
    public function testDefaultWithListWithVATAndLimitFixed(Closure $createList): void
    {
        $list     = $createList();
        $discount = DiscountFactory::get()
            ->setOption(DiscountFactory::OPT_SUBJECT, $list)
            ->setOption(DiscountFactory::OPT_VALUE, 100)
            ->setOption(DiscountFactory::OPT_IS_VALUE_WITH_VAT, true)
            ->create();

        $this->assertInstanceOf(PositivePriceWithVAT::class, $discount);
        $this->assertTrue(
            min(count($list) * 25, 100) == $discount->getValue()
            || min(count($list) * 25, 100) == $discount->getValueWithVAT()
        );
    }

    /**
     * @small
     *
     * @coversNothing
     *
     * @param \Closure(): array<\MiBo\Prices\Contracts\Discountable> $createList
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceProvider::provideDiscountableList()
     */
    public function testDefaultWithListWithVATAndLimitPercentage(Closure $createList): void
    {
        $list     = $createList();
        $discount = DiscountFactory::get()
            ->setOption(DiscountFactory::OPT_SUBJECT, $list)
            ->setOption(DiscountFactory::OPT_VALUE, 100)
            ->setOption(DiscountFactory::OPT_TYPE, DiscountFactory::TYPE_PERCENTAGE)
            ->setOption(DiscountFactory::OPT_PERCENTAGE_VALUE, 100)
            ->setOption(DiscountFactory::OPT_IS_VALUE_WITH_VAT, true)
            ->create();

        $this->assertInstanceOf(PositivePriceWithVAT::class, $discount);
        $this->assertTrue(
            min(count($list) * 25, 100) == $discount->getValueWithVAT()
            || min(count($list) * 25, 100) == $discount->getValue()
        );
    }

    /**
     * @small
     *
     * @coversNothing
     *
     * @param string $option
     * @param mixed $value
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\PriceProvider::provideFailingOptionList()
     */
    public function testFailingOptions(
        string $option,
        mixed $value
    ): void
    {
        $factory = DiscountFactory::get();

        $this->expectException(ValueError::class);

        $factory->setOption($option, $value);
    }

    /**
     * @small
     *
     * @coversNothing
     *
     * @return void
     */
    public function testCustomType(): void
    {
        DiscountFactory::customType(
            'test',
            static function(
                iterable $subject,
                PositivePrice|PositivePriceWithVAT $discount
            ): PositivePrice|PositivePriceWithVAT
            {
                return $discount;
            }
        );

        $factory = DiscountFactory::get();

        $factory->setOption(DiscountFactory::OPT_TYPE, 'test');
        $factory->setOption(DiscountFactory::OPT_SUBJECT, []);

        $this->assertEquals(0, $factory->create()->getValue());
    }
}
