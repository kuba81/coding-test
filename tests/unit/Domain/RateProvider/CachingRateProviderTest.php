<?php

namespace App\Domain\RateProvider;

use App\Domain\CacheInterface;
use App\Domain\ConversionRate;
use App\Domain\RateProviderInterface;
use Mockery;
use Mockery\MockInterface;
use TestCase;

class CachingRateProviderTest extends TestCase
{
    public function testWillReturnCachedContentWhenAvailable(): void
    {
        /** @var MockInterface|RateProviderInterface $nextProviderMock */
        $nextProviderMock = Mockery::mock(RateProviderInterface::class);
        $nextProviderMock->shouldNotReceive('getConversionRate');

        /** @var MockInterface|CacheInterface $cacheMock */
        $cacheMock = Mockery::mock(CacheInterface::class);
        $cacheMock
            ->shouldReceive('get')
            ->once()
            ->with('GBP', 'USD')
            ->andReturn(1.29);

        $cachingProvider = new CachingRateProvider($nextProviderMock, $cacheMock);

        try {
            $rate = $cachingProvider->getConversionRate('GBP', 'USD');

            $this->assertEquals(1.29, $rate->getValue());
            $this->assertEquals('cache', $rate->getSource());
        } finally {
            Mockery::close();
        }
    }

    public function testWillReturnCachedContentWhenInverseRateIsAvailable(): void
    {
        /** @var MockInterface|RateProviderInterface $nextProviderMock */
        $nextProviderMock = Mockery::mock(RateProviderInterface::class);
        $nextProviderMock->shouldNotReceive('getConversionRate');

        /** @var MockInterface|CacheInterface $cacheMock */
        $cacheMock = Mockery::mock(CacheInterface::class);
        $cacheMock
            ->shouldReceive('get')
            ->with('USD', 'GBP')
            ->andReturn(null);

        $cacheMock
            ->shouldReceive('get')
            ->with('GBP', 'USD')
            ->andReturn(1.29);

        $cachingProvider = new CachingRateProvider($nextProviderMock, $cacheMock);

        $rate = $cachingProvider->getConversionRate('USD', 'GBP');

        $this->assertEqualsWithDelta(0.7752, $rate->getValue(), 0.0001);
        $this->assertEquals('cache', $rate->getSource());

        Mockery::close();
    }

    public function testWillCacheRatesFromNextProviderWhenCachedContentNotAvailable(): void
    {
        $dummyProvider = new DummyRateProvider([
            'GBP:EUR' => new ConversionRate(1.14, 'dummy'),
        ]);

        /** @var MockInterface|CacheInterface $cacheMock */
        $cacheMock = Mockery::mock(CacheInterface::class);
        $cacheMock
            ->shouldReceive('get')
            ->once()
            ->with('GBP', 'EUR')
            ->andReturnNull();

        $cacheMock
            ->shouldReceive('get')
            ->once()
            ->with('EUR', 'GBP')
            ->andReturnNull();

        $cacheMock
            ->shouldReceive('put')
            ->once()
            ->with('GBP', 'EUR', 1.14);

        $cachingProvider = new CachingRateProvider($dummyProvider, $cacheMock);

        try {
            $rate = $cachingProvider->getConversionRate('GBP', 'EUR');

            $this->assertEquals(1.14, $rate->getValue());
            $this->assertEquals('dummy', $rate->getSource());
        } finally {
            Mockery::close();
        }
    }
}
