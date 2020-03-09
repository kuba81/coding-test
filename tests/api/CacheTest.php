<?php

use App\Domain\Exchange\CacheInterface;

class CacheTest extends TestCase
{
    public function testClear()
    {
        $cacheMock = Mockery::mock(CacheInterface::class);

        $cacheMock->shouldReceive('purge')->once();

        $this->app->instance(CacheInterface::class, $cacheMock);

        $this->json('GET', 'api/cache/clear')->seeJson([
            'error' => 0,
            'msg' => 'OK'
        ])->seeStatusCode(200);

        Mockery::close();
    }
}
