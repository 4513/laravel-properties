<?php

declare(strict_types=1);

namespace MiBo\Properties\Tests\Casting;

use Illuminate\Database\Eloquent\Model;
use MiBo\Properties\Data\Casting\NumericPropertyAttribute;
use MiBo\Properties\Tests\LaravelTestCase;
use MiBo\Properties\Area;
use MiBo\Properties\Length;
use MiBo\Properties\Pure;
use MiBo\Properties\Units\Area\SquareKiloMeter;
use MiBo\Properties\Units\Length\Foot;
use MiBo\Properties\Units\Length\Meter;
use MiBo\Properties\Units\Length\MilliMeter;
use Throwable;

/**
 * Class NumericPropRetrieveCastingTest
 *
 * @package MiBo\Properties\Tests\Casting
 *
 * @author 9I214
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
class NumericPropRetrieveCastingTest extends LaravelTestCase
{
    /**
     * @small
     *
     * @coversNothing
     *
     * @param int|float $value
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\NumericPropCastingProvider::provideRawValue()
     */
    public function testPureCasting(int|float $value): void
    {
        $caster   = new NumericPropertyAttribute();
        $newValue = $caster->get(
            new class extends Model {
            },
            'test',
            $value,
            []
        );

        $this->assertEquals(
            $newValue->getValue(),
            $value,
            "The value {$newValue->getValue()} does not match the provided $value."
        );
        $this->assertInstanceOf(Pure::class, $newValue);
    }

    /**
     * @small
     *
     * @coversNothing
     *
     * @param int|float $value
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\NumericPropCastingProvider::provideRawValue()
     */
    public function testLengthAloneCasting(int|float $value): void
    {
        $caster   = new NumericPropertyAttribute(Length::class);
        $newValue = $caster->get(
            new class extends Model {
            },
            'test',
            $value,
            []
        );

        $this->assertEquals(
            $newValue->getValue(),
            $value,
            "The value {$newValue->getValue()} does not match the provided $value."
        );
        $this->assertInstanceOf(Length::class, $newValue);
        $this->assertSame('meter', $newValue->getUnit()->getName());
    }

    /**
     * @small
     *
     * @coversNothing
     *
     * @param int|float $value
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\NumericPropCastingProvider::provideRawValue()
     */
    public function testLengthCasting(int|float $value): void
    {
        $caster   = new NumericPropertyAttribute('property-' . Length::class);
        $newValue = $caster->get(
            new class extends Model {
            },
            'test',
            $value,
            []
        );

        $this->assertEquals(
            $newValue->getValue(),
            $value,
            "The value {$newValue->getValue()} does not match the provided $value."
        );
        $this->assertInstanceOf(Length::class, $newValue);
        $this->assertSame('meter', $newValue->getUnit()->getName());
    }

    /**
     * @small
     *
     * @coversNothing
     *
     * @param int|float $value
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\NumericPropCastingProvider::provideRawValue()
     */
    public function testLengthWithFeetCasting(int|float $value): void
    {
        $caster   = new NumericPropertyAttribute(
            'property-' . Length::class,
            'unit-' . Foot::class
        );
        $newValue = $caster->get(
            new class extends Model {
            },
            'test',
            $value,
            []
        );

        $this->assertEquals(
            $newValue->getValue(),
            $value,
            "The value {$newValue->getValue()} does not match the provided $value."
        );
        $this->assertInstanceOf(Length::class, $newValue);
        $this->assertSame('foot', $newValue->getUnit()->getName());
    }

    /**
     * @small
     *
     * @coversNothing
     *
     * @param int|float $value
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\NumericPropCastingProvider::provideRawValue()
     */
    public function testSpecifiedUnitOnlyCasting(int|float $value): void
    {
        $caster   = new NumericPropertyAttribute('unit-' . MilliMeter::class);
        $newValue = $caster->get(
            new class extends Model {
            },
            'test',
            $value,
            []
        );

        $this->assertEquals(
            $newValue->getValue(),
            $value,
            "The value {$newValue->getValue()} does not match the provided $value."
        );
        $this->assertInstanceOf(Length::class, $newValue);
        $this->assertSame('millimeter', $newValue->getUnit()->getName());
    }

    /**
     * @small
     *
     * @coversNothing
     *
     * @param int|float $value
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\NumericPropCastingProvider::provideRawValue()
     */
    public function testSIUnitCasting(int|float $value): void
    {
        $caster   = new NumericPropertyAttribute(
            'property-' . Area::class,
            'unit-KILO'
        );
        $newValue = $caster->get(
            new class extends Model {
            },
            'test',
            $value,
            []
        );

        $this->assertEquals(
            $newValue->getValue(),
            $value,
            "The value {$newValue->getValue()} does not match the provided $value."
        );
        $this->assertInstanceOf(Area::class, $newValue);
        $this->assertSame('square kilometer', $newValue->getUnit()->getName());
    }

    /**
     * @small
     *
     * @coversNothing
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\NumericPropCastingProvider::provideRawValue()
     */
    public function testUselessCasting(): void
    {
        $caster   = new NumericPropertyAttribute(
            'property-' . Area::class,
            'unit-KILO'
        );
        $newValue = $caster->get(
            new class extends Model {
            },
            'test',
            new Area(10, SquareKiloMeter::get()),
            []
        );

        $this->assertEquals(
            $newValue->getValue(),
            10,
            "The value {$newValue->getValue()} does not match the provided 10."
        );
        $this->assertInstanceOf(Area::class, $newValue);
        $this->assertSame('square kilometer', $newValue->getUnit()->getName());
    }

    /**
     * @small
     *
     * @coversNothing
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\TestingData\Providers\NumericPropCastingProvider::provideRawValue()
     */
    public function testInvalidCombinationCasting(): void
    {
        $caster = new NumericPropertyAttribute(
            'property-' . Area::class,
            'unit-' . Meter::class
        );

        $this->expectException(Throwable::class);

        $caster->get(
            new class extends Model {
            },
            'test',
            100,
            []
        );
    }
}
