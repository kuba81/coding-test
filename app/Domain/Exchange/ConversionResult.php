<?php

namespace App\Domain\Exchange;

class ConversionResult
{
    /**
     * @var float
     */
    private $value;

    /**
     * @var string
     */
    private $source;

    public function __construct(float $value, string $source)
    {
        $this->value = $value;
        $this->source = $source;
    }

    public function getValue(?int $precision = null): float
    {
        return $precision !== null ? round($this->value, $precision) : $this->value;
    }

    public function getSource(): string
    {
        return $this->source;
    }
}
