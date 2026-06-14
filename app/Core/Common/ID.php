<?php

namespace App\Core\Common;

abstract class ID 
{
    private readonly string|null $value;

    public function __construct(string|null $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }

    public function equals(ID $other): bool
    {
        return $this->value === $other->value;
    }

    public function isDefined(): bool
    {
        return $this->value !== null;
    }

    public function isNull(): bool
    {
        return $this->value === null;
    }
}