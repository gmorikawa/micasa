<?php

namespace App\Core\Auth;

/**
 * Interface for hashing and verifying passwords.
 */
interface PasswordHasher
{
    public function hash(PlainPassword $plain): HashedPassword;

    public function verify(PlainPassword $plain, HashedPassword $hashed): bool;
}
