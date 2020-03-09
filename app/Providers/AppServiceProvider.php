<?php

namespace App\Providers;

use App\Domain\Exchange\Cache\EloquentCache;
use App\Domain\Exchange\CacheInterface;
use App\Domain\Exchange\Converter;
use App\Domain\Exchange\RateProvider\ApiExchangeRateProvider;
use App\Domain\Exchange\RateProvider\CachingRateProvider;
use App\Domain\Exchange\RateProviderInterface;
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

        $this->app->singleton(CacheInterface::class, function () {
            return new EloquentCache;
        });

        $this->app->bind(RateProviderInterface::class, function (Container $container) {
            $realProvider = new ApiExchangeRateProvider($container->make(ClientInterface::class));
            $cache = $container->make(CacheInterface::class);

            return new CachingRateProvider($realProvider, $cache);
        });

        $this->app->bind(Converter::class, function (Container $container) {
            return new Converter($container->make(RateProviderInterface::class));
        });
    }
}
