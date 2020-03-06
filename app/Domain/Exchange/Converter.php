<?php

namespace App\Domain\Exchange;

class Converter
{
    /**
     * @var RatesProviderInterface
     */
    private $rateProvider;

    public function __construct(RatesProviderInterface $rateProvider)
    {
        $this->rateProvider = $rateProvider;
    }

    public function convert(string $from, string $to, float $value): ConversionResult
    {
        $conversionRates = $this->rateProvider->getConversionRates();

        $valueInBaseCurrency = $value / $conversionRates->getRate($from);
        $converted = $valueInBaseCurrency * $conversionRates->getRate($to);

        return new ConversionResult($converted, $conversionRates->getSource());
    }
}
