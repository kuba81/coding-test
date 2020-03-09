<?php

namespace App\Domain\Exchange\Cache;

use App\CachedConversionRate;
use Carbon\Carbon;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use TestCase;

class EloquentCacheTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /**
     * @var EloquentCache
     */
    private $cache;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cache = $this->app->get(EloquentCache::class);
    }

    private function insertRateIntoDatabase(string $key, float $value, int $expiration)
    {
        factory(CachedConversionRate::class)->create([
            'key' => $key,
            'value' => $value,
            'expiration' => $expiration,
        ]);
    }

    public function testCacheWillInitiallyBeEmpty(): void
    {
        $this->assertEquals(CachedConversionRate::all()->count(), 0);
    }

    public function testWillPersistRateInTheDatabase(): void
    {
        $testNow = Carbon::parse('2020-03-09T22:00:00Z');

        try {
            Carbon::setTestNow($testNow);

            $this->cache->put('GBP', 'USD', 1.29);

            $expectedExpiration = $testNow->getTimestamp() + config('cache.ttl_seconds');

            $this->seeInDatabase('cached_conversion_rates', [
                'key' => 'GBP:USD',
                'value' => 1.29,
                'expiration' => $expectedExpiration
            ]);
        } finally {
            Carbon::setTestNow();
        }
    }

    public function testWillReturnRateIfInDatabaseAndNotExpired(): void
    {
        $expiry = Carbon::parse('2020-03-09T22:00:00Z');

        try {
            $this->insertRateIntoDatabase('GBP:USD', 1.29, $expiry->getTimestamp());

            Carbon::setTestNow($expiry->subSecond());

            $this->assertEquals(1.29, $this->cache->get('GBP', 'USD'));
        } finally {
            Carbon::setTestNow();
        }
    }

    public function testWillDeleteAndNotReturnRateIfExpired(): void
    {
        $expiry = Carbon::parse('2020-03-09T22:00:00Z');

        try {
            $this->insertRateIntoDatabase('GBP:USD', 1.29, $expiry->getTimestamp());

            Carbon::setTestNow($expiry);

            $this->assertNull($this->cache->get('GBP', 'USD'));

            $this->notSeeInDatabase('cached_conversion_rates', [
                'key' => 'GBP:USD',
            ]);
        } finally {
            Carbon::setTestNow();
        }
    }
}
