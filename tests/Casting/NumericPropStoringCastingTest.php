<?php

declare(strict_types=1);

namespace MiBo\Properties\Tests\Casting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use MiBo\Properties\Data\Casting\NumericPropertyAttribute;
use MiBo\Properties\Tests\LaravelTestCase;
use MiBo\Properties\Length;
use MiBo\Properties\Pure;
use MiBo\Properties\Units\Area\SquareKiloMeter;
use MiBo\Properties\Units\Length\DeciMeter;
use MiBo\Properties\Units\Length\Foot;
use MiBo\Properties\Units\Length\KiloMeter;
use MiBo\Properties\Units\Length\Meter;
use MiBo\Properties\Units\Length\MicroMeter;
use MiBo\Properties\Units\Length\Mile;
use MiBo\Properties\Units\Length\MilliMeter;
use Throwable;

/**
 * Class NumericPropStoringCastingTest
 *
 * @package MiBo\Properties\Tests\Casting
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 6I012
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
class NumericPropStoringCastingTest extends LaravelTestCase
{
    use RefreshDatabase;

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
        $value          = new Pure($value);
        $model          = $this->createModel(NumericPropertyAttribute::class);
        $model->numprop = $value;

        $model->save();
        $model->refresh();

        $this->assertEquals(
            $value->getValue(),
            $model->numprop->getValue(),
            "The value {$model->numprop->getValue()} does not match the provided {$value->getValue()}."
        );
        $this->assertInstanceOf(Pure::class, $model->numprop);
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
        $value          = new Length($value, new Meter());
        $model          = $this->createModel(NumericPropertyAttribute::class . ':' . Length::class);
        $model->numprop = $value;

        $model->save();
        $model->refresh();

        $this->assertEquals(
            $value->getValue(),
            $model->numprop->getValue(),
            "The value {$model->numprop->getValue()} does not match the provided {$value->getValue()}."
        );
        $this->assertInstanceOf(Length::class, $model->numprop);
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
    public function testLengthConvertingRequiredCasting(int|float $value): void
    {
        $value = new Length($value, Meter::get());
        $model = $this->createModel(NumericPropertyAttribute::class . ':' . Length::class);

        $value->convertToUnit(match (rand(0, 5)) {
            0 => MilliMeter::get(),
            1 => MicroMeter::get(),
            2 => KiloMeter::get(),
            3 => DeciMeter::get(),
            4 => Mile::get(),
            5 => Foot::get(),
            default => Meter::get(),
        });

        $model->numprop = $value;

        $model->save();
        $model->refresh();

        $value->convertToUnit(Meter::get());

        $this->assertEquals(
            $value->getValue(),
            $model->numprop->getValue(),
            "The value {$model->numprop->getValue()} does not match the provided {$value->getValue()}."
        );
        $this->assertInstanceOf(Length::class, $model->numprop);
        $this->assertTrue($model->numprop->getUnit()->is(Meter::get()));
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
        $value          = new Length($value, Meter::get());
        $model          = $this->createModel(
            NumericPropertyAttribute::class . ':property-' . Length::class . ',unit-' . Foot::class
        );
        $model->numprop = $value;

        $model->save();
        $model->refresh();

        $value->convertToUnit(Foot::get());
        $this->assertEquals(
            round($value->getValue()),
            round($model->numprop->getValue()),
            "The value {$model->numprop->getValue()} does not match the provided {$value->getValue()}."
        );
        $this->assertInstanceOf(Length::class, $model->numprop);
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
        $value          = new Length($value, Meter::get());
        $model          = $this->createModel(
            NumericPropertyAttribute::class . ':property-' . Length::class . ',unit-MILLI'
        );
        $model->numprop = $value;

        $model->save();
        $model->refresh();

        $value->convertToUnit(MilliMeter::get());

        $this->assertEquals(
            round($value->getValue()),
            round($model->numprop->getValue()),
            "The value {$model->numprop->getValue()} does not match the provided {$value->getValue()}."
        );
        $this->assertInstanceOf(Length::class, $model->numprop);
        $this->assertTrue($model->numprop->getUnit()->is(MilliMeter::get()));
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
    public function testInvalidCombinationCasting(int|float $value): void
    {
        $this->expectException(Throwable::class);

        $value          = new Length($value, Meter::get());
        $model          = $this->createModel(
            NumericPropertyAttribute::class . ':property-' . Length::class . ',unit-' . SquareKiloMeter::class
        );
        $model->numprop = $value;

        $model->save();
        $model->refresh();

        $this->assertEquals(
            round($value->getValue()),
            round($model->numprop->getValue()),
            "The value {$model->numprop->getValue()} does not match the provided {$value->getValue()}."
        );
    }

    private function createModel(string $cast): Model
    {
        return new class ([], $cast) extends Model
        {
            protected $guarded = [];

            protected $fillable = ['numprop'];

            protected $visible = ['numprop'];

            protected $table = 'test_table';

            public $timestamps = false;

            public function __construct(array $attributes = [], $cast = '')
            {
                $this->casts = ['numprop' => $cast];

                parent::__construct($attributes);
            }
        };
    }

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->afterApplicationCreated(
            function(): void {
                Schema::create('test_table', static function(Blueprint $table): void {
                    $table->id();
                    $table->integer('numprop')->default(0);
                });
            }
        );
        parent::setUp();
    }
}
