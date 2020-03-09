<?php

use App\Domain\Exchange\ConversionRate;
use App\Domain\Exchange\RateProvider\DummyRateProvider;
use App\Domain\Exchange\RateProviderInterface;

class ExchangeTest extends TestCase
{
    public function testInfoEndpointShouldReturnAuthor(): void
    {
        $this->json('GET', '/api/exchange/info')->seeJson([
            'error' => 0,
            'msg' => 'API written by Kuba Stawiarski',
        ]);
    }

    public function testExchangeShouldReturnRoundedValuesBasedOnResponseFromThirdPartyApiResponse(): void
    {
        $this->setupRateProviderStub([
            'GBP:USD' => 12.3456789
        ]);

        $this->json('GET', '/api/exchange/100/GBP/USD')->seeJson([
            'amount' => 1234.57,
            'error' => 0,
            'fromCache' => 0
        ]);
    }

    /**
     * @param string $from
     * @param string $to
     * @param string $rejected
     *
     * @dataProvider invalidCurrencyProvider
     */
    public function testShouldReturnErrorIfFromCurrencyIsNotSupported(string $from, string $to, string $rejected): void
    {
        $this->setupRateProviderStub([]);

        $url = sprintf('/api/exchange/100/%s/%s', $from, $to);

        $expectedErrorMessage = sprintf('currency code %s not supported', $rejected);

        $this->json('GET', $url)->seeJson([
            'error' => 1,
            'msg' => $expectedErrorMessage
        ])->seeStatusCode(422);
    }

    public function invalidCurrencyProvider()
    {
        return [
            // from, to, expected currency in error
            [ 'GBP', 'DEF', 'DEF' ],
            [ 'ABC', 'GBP', 'ABC' ],
            [ 'ABC', 'DEF', 'ABC' ],
        ];
    }

    private function setupRateProviderStub(array $rates)
    {
        $provider = new DummyRateProvider(collect($rates)->map(function ($value) {
            return new ConversionRate($value);
        })->toArray());

        app()->instance(RateProviderInterface::class, $provider);
    }
}
