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

    public function testExchangeShouldReturnValidPlaceholderResponse(): void
    {
        $this->get('/api/exchange/100/GBP/EUR')->seeJson([
            'amount' => 100,
            'error' => 0,
            'fromCache' => 0
        ]);
    }
}
