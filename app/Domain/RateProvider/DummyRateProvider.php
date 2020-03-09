<?php

namespace App\Domain\RateProvider;

use App\Domain\ConversionRate;
use App\Domain\RateProviderInterface;

class DummyRateProvider implements RateProviderInterface
{
    /**
     * @var ConversionRate[] keyed by currencies concatenated with a colon, eg: GBP:USD
     */
    private $rates;

    public function __construct(array $rates)
    {
        $this->rates = $rates;
    }

    public function getConversionRate(string $from, string $to): ConversionRate
    {
        $key = sprintf('%s:%s', $from, $to);

        return $this->rates[$key];
    }
}
