<?php

class CacheTest extends TestCase
{
    public function testClear()
    {
        $this->markTestSkipped('Needs reimplementing');

        $this->json('GET', 'api/cache/clear')->seeJson([
            'error' => 0,
            'msg' => 'OK'
        ])->seeStatusCode(200);
    }
}
