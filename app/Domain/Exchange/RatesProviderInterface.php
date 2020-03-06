<?php

namespace App\Domain\Exchange;

use App\Domain\Exchange\RateProvider\SourceException;

interface RatesProviderInterface
{
    /**
     * @return ConversionRates
     * @throws SourceException
     */
    public function getConversionRates(): ConversionRates;
}
