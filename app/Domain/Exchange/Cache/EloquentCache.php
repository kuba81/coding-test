<?php

namespace App\Domain\Exchange\Cache;

use App\CachedConversionRate;
use App\Domain\Exchange\CacheInterface;
use Illuminate\Support\Carbon;

class EloquentCache implements CacheInterface
{
    public function get(string $from, string $to): ?float
    {
        /** @var CachedConversionRate $cachedEntity */
        $cachedEntity = CachedConversionRate::find($this->generateKey($from, $to));

        if (! $cachedEntity) {
            return null;
        }

        if ($this->isExpired($cachedEntity)) {
            $cachedEntity->delete();
            return null;
        }

        return $cachedEntity->value;
    }

    public function put(string $from, string $to, float $value)
    {
        $key = $this->generateKey($from, $to);

        if (! $cachedEntity = CachedConversionRate::find($key)) {
            $cachedEntity = new CachedConversionRate;
            $cachedEntity->key = $key;
        }

        $cachedEntity->value = $value;
        $cachedEntity->expiration = $this->generateExpirationTime();
        $cachedEntity->save();
    }

    private function isExpired(CachedConversionRate $cachedEntity): bool
    {
        return Carbon::now()->getTimestamp() >= $cachedEntity->expiration;
    }

    public function generateExpirationTime(): int
    {
        return Carbon::now()->addSeconds(config('cache.ttl_seconds'))->getTimestamp();
    }

    private function generateKey(string $from, string $to)
    {
        return sprintf('%s:%s', $from, $to);
    }

    public function purge(): void
    {
        CachedConversionRate::query()->delete();
    }
}
