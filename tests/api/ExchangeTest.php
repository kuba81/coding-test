<?php

class ExchangeTest extends TestCase
{
    public function testInfoEndpointShouldReturnAuthor(): void
    {
        $this->json('GET', '/api/exchange/info')->seeJson([
            'error' => 0,
            'msg' => 'API written by Kuba Stawiarski',
        ]);
    }
}
