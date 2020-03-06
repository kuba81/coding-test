<?php

use Illuminate\Support\Facades\Cache;

class CacheTest extends TestCase
{
    public function testClear()
    {
        Cache::shouldReceive('forget')
            ->once()
            ->with(config('cache.exchange_rates.key'));

        $this->json('GET', 'api/cache/clear')->seeJson([
            'error' => 0,
            'msg' => 'OK'
        ])->seeStatusCode(200);
    }
}
