<?php

namespace App\Domain\Exchange;

interface CacheInterface
{
    public function get(string $from, string $to): ?float;
    public function put(string $from, string $to, float $value);
}
