<?php

namespace App\Domain\Exchange;

use App\Domain\Exchange\RateProvider\DummyRatesProvider;
use TestCase;

class ConverterTest extends TestCase
{
    public function testWillCalculateValueUsingProvidedRates(): void
    {
        $provider = new DummyRatesProvider(new ConversionRates([
            'GBP' => 0.8667,
            'USD' => 1.1187,
        ]));

        $converter = new Converter($provider);

        $converted = $converter->convert('GBP', 'USD', 10);

        $this->assertEqualsWithDelta(12.9075, $converted, 0.0001);
    }
}
