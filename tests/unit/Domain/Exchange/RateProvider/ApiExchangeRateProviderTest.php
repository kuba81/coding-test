<?php

namespace App\Domain\Exchange\RateProvider;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use TestCase;

class ApiExchangeRatesProviderTest extends TestCase
{
    public function testReturnValuesBasedOnResponseFromTheApi(): void
    {
        $body = \GuzzleHttp\json_encode([
            'rates' => [
                'GBP' => 0.8667,
            ],
            'base' => 'EUR',
            'date' => '2019-03-05'
        ]);

        $remoteApiResponseStub = new MockHandler([
            new Response(200, [], $body),
        ]);

        $handlerStack = HandlerStack::create($remoteApiResponseStub);
        $client = new Client(['handler' => $handlerStack]);

        $ratesProvider = new ApiExchangeRatesProvider($client);

        $rates = $ratesProvider->getConversionRates();

        $this->assertEqualsWithDelta(0.87, $rates->getRate('GBP'), 0.01);
        $this->assertEquals(1, $rates->getRate('EUR'));
    }
}
