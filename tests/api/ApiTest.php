<?php

class ApiTest extends TestCase
{
    /**
     * @param string $url
     * @dataProvider invalidUrlProvider
     */
    public function testShouldReturn404ErrorOnUnknownUrl(string $url): void
    {
        $this->json('GET', $url)->seeJson([
            'error' => 1,
            'msg' => 'invalid request',
        ])->seeStatusCode(404);
    }

    public function invalidUrlProvider(): array
    {
        return [
            [ '/api' ],
            [ '/not-a-valid-url' ],
            [ '/i/am/also/invalid' ],
            [ '/api/exchange' ],
        ];
    }
}
