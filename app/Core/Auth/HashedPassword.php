<?php

namespace App\Core\Auth;

/**
 * Object to represent a hashed password from a string.
 * 
 * This is used to distinguish between a hashed password and a plain password, and to prevent accidentally using a hashed password as a plain password.
 */
class HashedPassword 
{
    public readonly string $value;

    public function __construct(public string $password)
    {
        $this->value = $password;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}