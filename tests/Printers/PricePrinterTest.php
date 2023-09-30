<?php

declare(strict_types=1);

namespace MiBo\Properties\Tests\Printers;

use Illuminate\Auth\AuthServiceProvider;
use Illuminate\Support\Str;
use MiBo\Properties\Contracts\NumericalProperty;
use MiBo\Properties\Printers\PricePrinter;

/**
 * Class PricePrinterTest
 *
 * @package MiBo\Properties\Tests\Printers
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
class PricePrinterTest extends PrinterTest
{
    /**
     * @small
     *
     * @coversNothing
     *
     * @param string $expectedResult
     * @param string $locale
     * @param \MiBo\Properties\Contracts\NumericalProperty $property
     * @param int|null $decimals
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PrinterProvider::getDataToFormat()
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PrinterProvider::getPricesToFormat()
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PrinterProvider::getDataWithNullableDecimals()
     */
    public function testPrintingProperty(
        string $expectedResult,
        string $locale,
        NumericalProperty $property,
        ?int $decimals = null
    ): void
    {
        if ($locale === 'en') {
            PricePrinter::$convertCurrencyByLocale = true;
        }

        parent::testPrintingProperty($expectedResult, $locale, $property, $decimals);
    }

    /**
     * @small
     *
     * @coversNothing
     *
     * @param string $expectedResult
     * @param string $locale
     * @param \MiBo\Properties\Contracts\NumericalProperty $property
     * @param int|null $decimal
     *
     * @return void
     *
     * @dataProvider \MiBo\Properties\Tests\Coverage\Providers\PrinterProvider::getPricesToSimpleFormat()
     */
    public function testPrintingString(
        string $expectedResult,
        string $locale,
        NumericalProperty $property,
        ?int $decimal = null
    )
    {
        parent::testPrintingString($expectedResult, $locale, $property, $decimal);
    }

    /**
     * @inheritDoc
     */
    protected function getPrinter(): PricePrinter
    {
        return new PricePrinter();
    }

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['config']['auth']    = [
            'defaults'  => ['guard' => 'web'],
            'guards'    => [
                'web' => [
                    'driver'   => 'session',
                    'provider' => 'test',
                ],
            ],
            'providers' => [
                'test' => [
                    'driver' => 'eloquent',
                    'model'  => null,
                ],
            ],
        ];
        $this->app['config']['session'] = [
            'driver'          => env('SESSION_DRIVER', 'array'),
            'lifetime'        => env('SESSION_LIFETIME', 120),
            'expire_on_close' => false,
            'encrypt'         => false,
            'files'           => storage_path('framework/sessions'),
            'connection'      => env('SESSION_CONNECTION'),
            'table'           => 'sessions',
            'store'           => env('SESSION_STORE'),
            'lottery'         => [
                2,
                100,
            ],
            'cookie'          => env(
                'SESSION_COOKIE',
                Str::slug(env('APP_NAME', 'laravel'), '_') . '_session'
            ),
            'path'            => '/',
            'domain'          => env('SESSION_DOMAIN'),
            'secure'          => env('SESSION_SECURE_COOKIE'),
            'http_only'       => true,
            'same_site'       => 'lax',
        ];

        $this->app->register(AuthServiceProvider::class);

        PricePrinter::$convertCurrencyByLocale = false;
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        PricePrinter::$convertCurrencyByLocale = true;

        parent::tearDown();
    }
}
