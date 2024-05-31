<?php

declare(strict_types=1);

namespace pointybeard\EcbExchangeRate\Tests\Unit;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use pointybeard\EcbExchangeRate\Facades\EcbExchangeRate;
use pointybeard\EcbExchangeRate\Providers\EcbExchangeRateServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * Allowed numerical distance between two values to consider them equal.
     */
    protected const FLOAT_DELTA = 0.0000000000001;

    /**
     * Load package service provider.
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [EcbExchangeRateServiceProvider::class];
    }

    /**
     * Load package alias.
     *
     * @param  Application  $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'ecb-exchange-rates' => EcbExchangeRate::class,
        ];
    }
}
