<?php

namespace App\Domain\Exchange\RateProvider;

use App\Domain\Exchange\ConversionRates;
use App\Domain\Exchange\RatesProviderInterface;
use Illuminate\Support\Facades\Cache;

class CachingRatesProviderDecorator implements RatesProviderInterface
{
    /**
     * @var RatesProviderInterface
     */
    private $nextProvider;

    public function __construct(RatesProviderInterface $nextProvider)
    {
        $this->nextProvider = $nextProvider;
    }

    public function getConversionRates(): ConversionRates
    {
        $cacheKey = config('cache.exchange_rates.key');

        if ($rates = Cache::get($cacheKey)) {
            return new ConversionRates($rates, 'cache');
        }

        $uncachedConversionRates = $this->nextProvider->getConversionRates();

        Cache::put($cacheKey, $uncachedConversionRates->getRates(), config('cache.exchange_rates.ttl_seconds'));

        return $uncachedConversionRates;
    }
}
