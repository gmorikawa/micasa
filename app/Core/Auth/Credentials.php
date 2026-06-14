<?php

namespace App\Core\Auth;

use App\Core\User\Email;

class Credentials
{
    private Email $email;
    private PlainPassword $password;

    public function __construct(Email $email, PlainPassword $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPassword(): PlainPassword
    {
        return $this->password;
    }
}