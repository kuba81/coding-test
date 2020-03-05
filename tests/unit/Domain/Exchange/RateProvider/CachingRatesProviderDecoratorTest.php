<?php

namespace App\Domain\Exchange\RateProvider;

use App\Domain\Exchange\ConversionRates;
use Illuminate\Support\Facades\Cache;
use TestCase;

class CachingRatesProviderDecoratorTest extends TestCase
{
    public function testWillReturnCachedContentWhenAvailable(): void
    {
        $dummyProvider = new DummyRatesProvider(new ConversionRates([
            'GBP' => 1,
            'USD' => 2
        ]));

        Cache::shouldReceive('get')
            ->once()
            ->with(config('cache.exchange_rates.key'))
            ->andReturn([
                'GBP' => 1,
                'USD' => 4
            ]);

        $cachingProvider = new CachingRatesProviderDecorator($dummyProvider);

        $conversionRates = $cachingProvider->getConversionRates();

        $this->assertEquals(4, $conversionRates->getRate('USD'));
        $this->assertEquals('cache', $conversionRates->getSource());
    }

    public function testWillCacheRatesFromNextProviderWhenCachedContentNotAvailable(): void
    {
        $originalRates = [
            'GBP' => 1,
            'USD' => 2
        ];

        $dummyProvider = new DummyRatesProvider(new ConversionRates($originalRates, 'test-rates-source'));

        Cache::shouldReceive('get')
            ->once()
            ->with(config('cache.exchange_rates.key'))
            ->andReturn(null);

        Cache::shouldReceive('put')
            ->once()
            ->with(config('cache.exchange_rates.key'), $originalRates, config('cache.exchange_rates.ttl_seconds'));

        $cachingProvider = new CachingRatesProviderDecorator($dummyProvider);

        $conversionRates = $cachingProvider->getConversionRates();

        $this->assertEquals(2, $conversionRates->getRate('USD'));
        $this->assertEquals('test-rates-source', $conversionRates->getSource());
    }
}
