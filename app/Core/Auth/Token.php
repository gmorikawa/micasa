<?php

namespace App\Core\Auth;

/**
 * Object to represent an authentication token from a string.
 */
class Token
{
    public readonly string $value;

    public function __construct(public string $token)
    {
        $this->value = $token;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
