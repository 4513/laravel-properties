<?php

declare(strict_types=1);

namespace MiBo\Properties\Tests\Coverage\Casting;

use MiBo\Properties\Tests\Casting\NumericPropStoringCastingTest as BaseTest;

/**
 * Class NumericPropStoringCastingTest
 *
 * @package MiBo\Properties\Tests\Coverage\Casting
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 6I012
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 *
 * @coversDefaultClass \MiBo\Properties\Data\Casting\NumericPropertyAttribute
 */
class NumericPropStoringCastingTest extends BaseTest
{
    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::set
     *
     * @param int|float $value
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\NumericPropCastingProvider::provideRawValue()
     */
    public function testPureCasting(int|float $value): void
    {
        parent::testPureCasting($value);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::set
     *
     * @param int|float $value
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\NumericPropCastingProvider::provideRawValue()
     */
    public function testLengthAloneCasting(int|float $value): void
    {
        parent::testLengthAloneCasting($value);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::set
     *
     * @param int|float $value
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\NumericPropCastingProvider::provideRawValue()
     */
    public function testLengthConvertingRequiredCasting(int|float $value): void
    {
        parent::testLengthConvertingRequiredCasting($value);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::set
     *
     * @param int|float $value
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\NumericPropCastingProvider::provideRawValue()
     */
    public function testLengthWithFeetCasting(int|float $value): void
    {
        parent::testLengthWithFeetCasting($value);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::set
     *
     * @param int|float $value
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\NumericPropCastingProvider::provideRawValue()
     */
    public function testSIUnitCasting(int|float $value): void
    {
        parent::testSIUnitCasting($value);
    }

    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::set
     *
     * @param int|float $value
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\NumericPropCastingProvider::provideRawValue()
     */
    public function testInvalidCombinationCasting(int|float $value): void
    {
        parent::testInvalidCombinationCasting($value);
    }
}
