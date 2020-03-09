<?php

namespace App\Domain\Exchange\RateProvider;

use App\Domain\Exchange\ConversionRate;
use App\Domain\Exchange\RateProviderInterface;

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
