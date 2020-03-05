<?php

namespace App\Providers;

use App\Domain\Exchange\Converter;
use App\Domain\Exchange\RateProvider\ApiExchangeRatesProvider;
use App\Domain\Exchange\RatesProviderInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ClientInterface::class, Client::class);

        $this->app->bind(RatesProviderInterface::class, function (Container $container) {
            return new ApiExchangeRatesProvider($container->make(ClientInterface::class));
        });

        $this->app->bind(Converter::class, function (Container $container) {
            return new Converter($container->make(RatesProviderInterface::class));
        });
    }
}
