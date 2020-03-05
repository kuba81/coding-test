<?php

namespace App\Domain\Exchange;

class ConversionRates
{
    /**
     * @var float[] rate => value pairs
     */
    private $rates;

    public function __construct($rates)
    {
        $this->rates = $rates;
    }

    public function getRate(string $currency): float
    {
        return $this->rates[$currency];
    }
}
