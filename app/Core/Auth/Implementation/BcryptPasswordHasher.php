<?php

namespace App\Core\Auth\Implementation;

use App\Core\Auth\HashedPassword;
use App\Core\Auth\PasswordHasher;
use App\Core\Auth\PlainPassword;

class BcryptPasswordHasher implements PasswordHasher
{
    public function hash(PlainPassword $plain): HashedPassword
    {
        return new HashedPassword(password_hash($plain->value, PASSWORD_BCRYPT));
    }

    public function verify(PlainPassword $plain, HashedPassword $hashed): bool
    {
        return password_verify($plain->value, $hashed->value);
    }
}