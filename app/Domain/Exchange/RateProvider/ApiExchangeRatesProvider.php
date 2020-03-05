<?php

namespace App\Domain\Exchange\RateProvider;

use App\Domain\Exchange\ConversionRates;
use App\Domain\Exchange\RatesProviderInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
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
            $response = $this->httpClient->request('GET', config('services.exchange-rates-api'));

            $ratesArray = $this->extractRatesArrayFromResponse($response);

            return new ConversionRates($ratesArray);
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

        $ratesArray = $parsedBody['rates'];
        $ratesArray[$baseRate] = 1;

        return $ratesArray;
    }
}
