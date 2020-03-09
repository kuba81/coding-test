<?php

namespace App\Providers;

use App\Domain\Cache\EloquentCache;
use App\Domain\CacheInterface;
use App\Domain\Converter;
use App\Domain\RateProvider\ApiExchangeRateProvider;
use App\Domain\RateProvider\CachingRateProvider;
use App\Domain\RateProviderInterface;
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
