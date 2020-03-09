<?php

namespace App\Domain;

use App\Domain\RateProvider\DummyRateProvider;
use TestCase;

class ConverterTest extends TestCase
{
    public function testWillCalculateValueUsingProvidedRates(): void
    {
        $provider = new DummyRateProvider([
            'GBP:USD' => new ConversionRate(1.290758048)
        ]);

        $converter = new Converter($provider);

        $conversionResult = $converter->convert('GBP', 'USD', 10);

        $this->assertEqualsWithDelta(12.9075, $conversionResult->getValue(), 0.0001);
    }
}
