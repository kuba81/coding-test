<?php

namespace App\Domain\Exchange;

class ConversionRate
{
    /**
     * @var string|null optional source
     */
    private $source;

    /**
     * @var float
     */
    private $value;

    public function __construct(float $value, string $source = null)
    {
        $this->source = $source;
        $this->value = $value;
    }

    public function getSource(): string
    {
        return $this->source ?? 'unspecified';
    }

    public function getValue(): float
    {
        return $this->value;
    }
}
