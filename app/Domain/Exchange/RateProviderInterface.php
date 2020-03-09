<?php

namespace App\Domain\Exchange;

use App\Domain\Exchange\RateProvider\SourceException;

interface RateProviderInterface
{
    /**
     * @param string $from
     * @param string $to
     *
     * @return ConversionRate
     * @throws SourceException
     */
    public function getConversionRate(string $from, string $to): ConversionRate;
}
