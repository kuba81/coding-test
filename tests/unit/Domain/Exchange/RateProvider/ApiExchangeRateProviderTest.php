<?php

namespace App\Domain\Exchange\RateProvider;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use TestCase;

class ApiExchangeRatesProviderTest extends TestCase
{
    public function testReturnCorrectExchangeRateWhenConvertingToBaseCurrency(): void
    {
        $client = $this->stubClient([
            'rates' => [
                'GBP' => 0.8667,
            ],
            'base' => 'EUR',
            'date' => '2019-03-05'
        ]);

        $ratesProvider = new ApiExchangeRateProvider($client);

        $toBaseRate = $ratesProvider->getConversionRate('GBP', 'EUR');

        $this->assertEqualsWithDelta(1.1538, $toBaseRate->getValue(), 0.0001);
    }

    public function testReturnCorrectExchangeRateWhenConvertingFromBaseCurrency(): void
    {
        $client = $this->stubClient([
            'rates' => [
                'GBP' => 0.8667,
            ],
            'base' => 'EUR',
            'date' => '2019-03-05'
        ]);

        $ratesProvider = new ApiExchangeRateProvider($client);

        $toBaseRate = $ratesProvider->getConversionRate('EUR', 'GBP');

        $this->assertEqualsWithDelta(0.8667, $toBaseRate->getValue(), 0.0001);
    }

    public function testReturnCorrectExchangeRateWhenConvertingToAnotherCurrency(): void
    {
        $client = $this->stubClient([
            'rates' => [
                'GBP' => 0.8667,
                'USD' => 1.1187,
            ],
            'base' => 'EUR',
            'date' => '2019-03-05'
        ]);

        $ratesProvider = new ApiExchangeRateProvider($client);

        $toBaseRate = $ratesProvider->getConversionRate('GBP', 'USD');

        $this->assertEqualsWithDelta(1.2907, $toBaseRate->getValue(), 0.0001);
    }

    public function stubClient(array $responseArray): Client
    {
        $body = \GuzzleHttp\json_encode($responseArray);

        $remoteApiResponseStub = new MockHandler([
            new Response(200, [], $body),
        ]);

        $handlerStack = HandlerStack::create($remoteApiResponseStub);

        return new Client(['handler' => $handlerStack]);
    }
}
