<?php

declare(strict_types=1);

namespace pointybeard\EcbExchangeRate\Providers;

use Illuminate\Support\ServiceProvider;
use pointybeard\EcbExchangeRate\Commands\FetchExchangeRate;
use pointybeard\EcbExchangeRate\Services\EcbScraperService;

class EcbExchangeRateServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/ecb-exchange-rate.php', 'ecb-exchange-rate');

        $this->app->singleton(EcbScraperService::class, function ($app) {
            return new EcbScraperService;
        });

        $this->app->alias(EcbScraperService::class, 'ecb-exchange-rate');
    }

    /**
     * @return void
     */
    public function boot()
    {
        $this->bindCommands();

        $this->registerCommands();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/ecb-exchange-rate.php' => config_path('ecb-exchange-rate.php'),
            ], 'config');
        }
    }

    private function registerCommands(): void
    {
        $this->commands([
            'command.ecb-exchange-rate:fetch',
        ]);
    }

    private function bindCommands(): void
    {
        $this->app->bind('command.ecb-exchange-rate:fetch', FetchExchangeRate::class);
    }
}
