<?php

namespace App\Domain;

interface CacheInterface
{
    public function get(string $from, string $to): ?float;
    public function put(string $from, string $to, float $value);
    public function purge(): void;
}
