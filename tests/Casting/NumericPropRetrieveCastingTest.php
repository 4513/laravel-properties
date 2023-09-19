<?php

declare(strict_types=1);

namespace MiBo\Prices\Tests\Casting;

use Illuminate\Database\Eloquent\Model;
use MiBo\Prices\Data\Casting\NumericPropertyAttribute;
use MiBo\Prices\Tests\LaravelTestCase;
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
 * @package MiBo\Prices\Tests\Casting
 *
 * @author 9I214
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 *
 * @coversDefaultClass \MiBo\Prices\Data\Casting\NumericPropertyAttribute
 */
class NumericPropRetrieveCastingTest extends LaravelTestCase
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
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\NumericPropCastingProvider::provideRawValue()
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
     * @covers ::__construct
     * @covers ::get
     *
     * @param int|float $value
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\NumericPropCastingProvider::provideRawValue()
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
     * @covers ::__construct
     * @covers ::get
     *
     * @param int|float $value
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\NumericPropCastingProvider::provideRawValue()
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
     * @covers ::__construct
     * @covers ::get
     *
     * @param int|float $value
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\NumericPropCastingProvider::provideRawValue()
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
     * @covers ::__construct
     * @covers ::get
     *
     * @param int|float $value
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\NumericPropCastingProvider::provideRawValue()
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
     * @covers ::__construct
     * @covers ::get
     *
     * @param int|float $value
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\NumericPropCastingProvider::provideRawValue()
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
     * @covers ::__construct
     * @covers ::get
     *
     * @param int|float $value
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\NumericPropCastingProvider::provideRawValue()
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
     * @covers ::__construct
     * @covers ::get
     *
     * @param int|float $value
     *
     * @return void
     *
     * @dataProvider \MiBo\Prices\Tests\TestingData\Providers\NumericPropCastingProvider::provideRawValue()
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
