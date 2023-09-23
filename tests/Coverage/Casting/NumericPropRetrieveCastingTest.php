<?php

declare(strict_types=1);

namespace MiBo\Prices\Tests\Coverage\Casting;

use MiBo\Prices\Tests\Casting\NumericPropRetrieveCastingTest as BaseTest;

/**
 * Class NumericPropRetrieveCastingTest
 *
 * @package MiBo\Prices\Tests\Coverage\Casting
 *
 * @author 9I214
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 *
 * @coversDefaultClass \MiBo\Prices\Data\Casting\NumericPropertyAttribute
 */
class NumericPropRetrieveCastingTest extends BaseTest
{
    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param int|float $value
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\Coverage\Providers\NumericPropCastingProvider::provideRawValue()
     */
    public function testPureCasting(int|float $value): void
    {
        parent::testPureCasting($value);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param int|float $value
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\Coverage\Providers\NumericPropCastingProvider::provideRawValue()
     */
    public function testLengthAloneCasting(int|float $value): void
    {
        parent::testLengthAloneCasting($value);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param int|float $value
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\Coverage\Providers\NumericPropCastingProvider::provideRawValue()
     */
    public function testLengthCasting(int|float $value): void
    {
        parent::testLengthCasting($value);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param int|float $value
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\Coverage\Providers\NumericPropCastingProvider::provideRawValue()
     */
    public function testLengthWithFeetCasting(int|float $value): void
    {
        parent::testLengthWithFeetCasting($value);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param int|float $value
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\Coverage\Providers\NumericPropCastingProvider::provideRawValue()
     */
    public function testSpecifiedUnitOnlyCasting(int|float $value): void
    {
        parent::testSpecifiedUnitOnlyCasting($value);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @param int|float $value
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\Coverage\Providers\NumericPropCastingProvider::provideRawValue()
     */
    public function testSIUnitCasting(int|float $value): void
    {
        parent::testSIUnitCasting($value);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\Coverage\Providers\NumericPropCastingProvider::provideRawValue()
     */
    public function testUselessCasting(): void
    {
        parent::testUselessCasting();
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::get
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\Coverage\Providers\NumericPropCastingProvider::provideRawValue()
     */
    public function testInvalidCombinationCasting(): void
    {
        parent::testInvalidCombinationCasting();
    }
}
