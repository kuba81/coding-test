<?php

namespace App\Domain\Exchange;

use TestCase;

class ConverterTest extends TestCase
{
    public function testWillCalculateValueUsingProvidedRates(): void
    {
        $dummyRatesProvider = new class implements RatesProviderInterface
        {
            public function getConversionRates(): ConversionRates
            {
                return new ConversionRates(
                    [
                        'GBP' => 0.8667,
                        'USD' => 1.1187,
                    ]
                );
            }
        };

        $converter = new Converter($dummyRatesProvider);

        $converted = $converter->convert('GBP', 'USD', 10);

        $this->assertEqualsWithDelta(12.9075, $converted, 0.0001);
    }
}
