<?php

declare(strict_types=1);

namespace MiBo\Properties\Tests\Coverage\Factories;

use Closure;
use MiBo\Properties\Exceptions\CouldNotApplyWholeAmountOfDiscountException;
use MiBo\Properties\Tests\Factories\DiscountFactoryTest as BaseTest;

/**
 * Class DiscountFactoryTest
 *
 * @package MiBo\Prices\Tests\Coverage\Factories
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 *
 * @coversDefaultClass \MiBo\Properties\Data\Factories\DiscountFactory
 */
class DiscountFactoryTest extends BaseTest
{
    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     * @covers ::clear
     * @covers ::apply
     * @covers ::create
     *
     * @return void
     */
    public function testDefault(): void
    {
        parent::testDefault();
    }

    /**
     * @small
     *
     * @covers ::get
     * @covers ::setOption
     * @covers ::clear
     * @covers ::apply
     * @covers ::create
     * @covers ::applyFixed
     *
     * @param \Closure(): array<\MiBo\Properties\Contracts\Discountable> $createList
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceProvider::provideDiscountableList()
     */
    public function testDefaultWithList(Closure $createList): void
    {
        parent::testDefaultWithList($createList);
    }

    /**
     * @small
     *
     * @covers ::get
     * @covers ::setOption
     * @covers ::clear
     * @covers ::apply
     * @covers ::create
     * @covers ::applyFixed
     *
     * @param \Closure(): array<\MiBo\Properties\Contracts\Discountable> $createList
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceProvider::provideDiscountableList()
     */
    public function testDefaultWithListWithVAT(Closure $createList): void
    {
        parent::testDefaultWithListWithVAT($createList);
    }

    /**
     * @small
     *
     * @covers ::get
     * @covers ::setOption
     * @covers ::clear
     * @covers ::apply
     * @covers ::create
     * @covers ::applyFixed
     *
     * @param \Closure(): array<\MiBo\Properties\Contracts\Discountable> $createList
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceProvider::provideDiscountableList()
     */
    public function testDefaultWithListWithFilter(Closure $createList): void
    {
        parent::testDefaultWithListWithFilter($createList);
    }

    /**
     * @small
     *
     * @covers ::get
     * @covers ::setOption
     * @covers ::clear
     * @covers ::apply
     * @covers ::create
     * @covers ::applyFixed
     * @covers \MiBo\Properties\Exceptions\CouldNotApplyWholeAmountOfDiscountException::__construct
     *
     * @param \Closure(): array<\MiBo\Properties\Contracts\Discountable> $createList
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceProvider::provideDiscountableList()
     */
    public function testDefaultWithListWithRequiredWholeSum(Closure $createList): void
    {
        parent::testDefaultWithListWithRequiredWholeSum($createList);
    }

    /**
     * @small
     *
     * @covers ::get
     * @covers ::setOption
     * @covers ::clear
     * @covers ::apply
     * @covers ::create
     * @covers ::applyFixed
     *
     * @param \Closure(): array<\MiBo\Properties\Contracts\Discountable> $createList
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceProvider::provideDiscountableList()
     */
    public function testDefaultWithListWithValue(Closure $createList): void
    {
        parent::testDefaultWithListWithValue($createList);
    }

    /**
     * @small
     *
     * @covers ::get
     * @covers ::setOption
     * @covers ::clear
     * @covers ::apply
     * @covers ::create
     * @covers ::applyFixed
     *
     * @param \Closure(): array<\MiBo\Properties\Contracts\Discountable> $createList
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceProvider::provideDiscountableList()
     */
    public function testDefaultWithListWithVATNone(Closure $createList): void
    {
        parent::testDefaultWithListWithVATNone($createList);
    }

    /**
     * @small
     *
     * @covers ::get
     * @covers ::setOption
     * @covers ::clear
     * @covers ::apply
     * @covers ::create
     * @covers ::applyPercentage
     *
     * @param \Closure(): array<\MiBo\Properties\Contracts\Discountable> $createList
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceProvider::provideDiscountableList()
     */
    public function testDefaultWithListWithPercentage(Closure $createList): void
    {
        parent::testDefaultWithListWithPercentage($createList);
    }

    /**
     * @small
     *
     * @covers ::get
     * @covers ::setOption
     * @covers ::clear
     * @covers ::apply
     * @covers ::create
     * @covers ::applyPercentage
     *
     * @param \Closure(): array<\MiBo\Properties\Contracts\Discountable> $createList
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceProvider::provideDiscountableList()
     */
    public function testDefaultWithListWithPercentageAndValue(Closure $createList): void
    {
        parent::testDefaultWithListWithPercentageAndValue($createList);
    }

    /**
     * @small
     *
     * @covers ::get
     * @covers ::setOption
     * @covers ::clear
     * @covers ::apply
     * @covers ::create
     * @covers ::applyFixed
     *
     * @param \Closure(): array<\MiBo\Properties\Contracts\Discountable> $createList
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceProvider::provideDiscountableList()
     */
    public function testDefaultWithCombinedVAT(Closure $createList): void
    {
        parent::testDefaultWithCombinedVAT($createList);
    }

    /**
     * @small
     *
     * @covers ::create
     *
     * @return void
     */
    public function testCombinedVATOutFiltered(): void
    {
        parent::testCombinedVATOutFiltered();
    }

    /**
     * @small
     *
     * @covers ::create
     *
     * @return void
     */
    public function testCombinedVATFiltered(): void
    {
        parent::testCombinedVATFiltered();
    }

    /**
     * @small
     *
     * @covers ::create
     *
     * @return void
     */
    public function testIncompatibleSubject(): void
    {
        parent::testIncompatibleSubject();
    }

    /**
     * @small
     *
     * @covers ::get
     * @covers ::setOption
     * @covers ::clear
     * @covers ::apply
     * @covers ::create
     * @covers ::applyFixed
     *
     * @param \Closure(): array<\MiBo\Properties\Contracts\Discountable> $createList
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceProvider::provideDiscountableList()
     */
    public function testDefaultWithCombinedVATWithFilter(Closure $createList): void
    {
        parent::testDefaultWithCombinedVATWithFilter($createList);
    }

    /**
     * @small
     *
     * @covers ::get
     * @covers ::setOption
     * @covers ::clear
     * @covers ::apply
     * @covers ::create
     * @covers ::applyFixed
     *
     * @param \Closure(): array<\MiBo\Properties\Contracts\Discountable> $createList
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceProvider::provideDiscountableList()
     */
    public function testDefaultWithListWithVATAndLimitFixed(Closure $createList): void
    {
        parent::testDefaultWithListWithVATAndLimitFixed($createList);
    }

    /**
     * @small
     *
     * @covers ::get
     * @covers ::setOption
     * @covers ::clear
     * @covers ::apply
     * @covers ::create
     * @covers ::applyPercentage
     *
     * @param \Closure(): array<\MiBo\Properties\Contracts\Discountable> $createList
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceProvider::provideDiscountableList()
     */
    public function testDefaultWithListWithVATAndLimitPercentage(Closure $createList): void
    {
        parent::testDefaultWithListWithVATAndLimitPercentage($createList);
    }

    /**
     * @small
     *
     * @covers ::setOption
     *
     * @param string $option
     * @param mixed $value
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PriceProvider::provideFailingOptionList()
     */
    public function testFailingOptions(
        string $option,
        mixed $value
    ): void
    {
        parent::testFailingOptions($option, $value);
    }

    /**
     * @small
     *
     * @covers ::customType
     * @covers ::setOption
     * @covers ::create
     *
     * @return void
     */
    public function testCustomType(): void
    {
        parent::testCustomType();
    }
}
