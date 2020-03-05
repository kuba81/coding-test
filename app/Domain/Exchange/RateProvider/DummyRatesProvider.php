<?php

namespace App\Domain\Exchange\RateProvider;

use App\Domain\Exchange\ConversionRates;
use App\Domain\Exchange\RatesProviderInterface;

class DummyRatesProvider implements RatesProviderInterface
{
    /**
     * @var ConversionRates
     */
    private $rates;

    public function __construct(ConversionRates $rates)
    {
        $this->rates = $rates;
    }

    public function getConversionRates(): ConversionRates
    {
        return $this->rates;
    }
}
