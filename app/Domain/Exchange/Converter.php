<?php

namespace App\Domain\Exchange;

class Converter
{
    /**
     * @var RateProviderInterface
     */
    private $rateProvider;

    public function __construct(RateProviderInterface $rateProvider)
    {
        $this->rateProvider = $rateProvider;
    }

    public function convert(string $from, string $to, float $value): ConversionResult
    {
        $conversionRate = $this->rateProvider->getConversionRate($from, $to);

        $converted = $conversionRate->getValue() * $value;

        return new ConversionResult($converted, $conversionRate->getSource());
    }
}
