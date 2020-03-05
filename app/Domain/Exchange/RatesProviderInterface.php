<?php

namespace App\Domain\Exchange;

interface RatesProviderInterface
{
    public function getConversionRates(): ConversionRates;
}
