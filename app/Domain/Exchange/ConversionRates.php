<?php

namespace App\Domain\Exchange;

class ConversionRates
{
    /**
     * @var float[] rate => value pairs
     */
    private $rates;

    /**
     * @var string|null optional source
     */
    private $source;

    public function __construct($rates, ?string $source = null)
    {
        $this->rates = $rates;
        $this->source = $source;
    }

    public function getRate(string $currency): float
    {
        return $this->rates[$currency];
    }

    /**
     * Returns rates as rate => value pairs
     *
     * @return float[]
     */
    public function getRates(): array
    {
        return $this->rates;
    }

    public function getSource(): string
    {
        return $this->source ?? 'unspecified';
    }
}
