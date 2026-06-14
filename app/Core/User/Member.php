<?php

namespace App\Core\User;

use App\Core\Auth\HashedPassword;

class Member extends User
{
    public function __construct(
        UserID $id,
        Email $email,
        HashedPassword $password
    ) {
        parent::__construct(
            $id,
            $email,
            $password,
            UserRole::MEMBER
        );
    }
}
