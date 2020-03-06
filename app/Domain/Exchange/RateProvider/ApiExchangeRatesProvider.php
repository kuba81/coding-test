<?php

namespace App\Domain\Exchange\RateProvider;

use App\Domain\Exchange\ConversionRates;
use App\Domain\Exchange\RatesProviderInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;

class ApiExchangeRatesProvider implements RatesProviderInterface
{
    /**
     * @var ClientInterface
     */
    private $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getConversionRates(): ConversionRates
    {
        try {
            $response = $this->httpClient->request('GET', config('services.exchange_rates_api'));

            $ratesArray = $this->extractRatesArrayFromResponse($response);

            return new ConversionRates($ratesArray, 'api');
        } catch (GuzzleException $e) {
            dd($e->getMessage());
            throw new SourceException('Unable to fetch exchange rates', 0, $e);
        }
    }

    private function extractRatesArrayFromResponse(ResponseInterface $response): array
    {
        $body = $response->getBody()->getContents();

        $parsedBody = \GuzzleHttp\json_decode($body, true);

        // we should validate the structure of the response here, but I’ll omit it as it is unnecessary
        // for the purpose of the coding test
        $baseRate = $parsedBody['base'];

        $ratesArray = Arr::only($parsedBody['rates'], config('currencies.supported'));
        $ratesArray[$baseRate] = 1;

        return $ratesArray;
    }
}
