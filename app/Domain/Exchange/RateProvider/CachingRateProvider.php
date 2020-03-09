<?php

namespace App\Domain\Exchange\RateProvider;

use App\Domain\Exchange\CacheInterface;
use App\Domain\Exchange\ConversionRate;
use App\Domain\Exchange\RateProviderInterface;

class CachingRateProvider implements RateProviderInterface
{
    /**
     * @var RateProviderInterface
     */
    private $nextProvider;

    /**
     * @var CacheInterface
     */
    private $conversionRateCache;

    public function __construct(RateProviderInterface $nextProvider, CacheInterface $cache)
    {
        $this->nextProvider = $nextProvider;
        $this->conversionRateCache = $cache;
    }

    public function getConversionRate(string $from, string $to): ConversionRate
    {
        if ($cachedValue = $this->conversionRateCache->get($from, $to)) {
            return new ConversionRate($cachedValue, 'cache');
        } elseif ($inverseCachedValue = $this->conversionRateCache->get($to, $from)) {
            return new ConversionRate(1 / $inverseCachedValue, 'cache');
        }

        $realConversionRate = $this->nextProvider->getConversionRate($from, $to);

        $this->conversionRateCache->put($from, $to, $realConversionRate->getValue());

        return $realConversionRate;
    }
}
