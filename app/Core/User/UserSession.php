<?php

namespace App\Core\User;

use App\Core\Auth\Token;

class UserSession
{
    private Token $token;
    private LoggedUser $loggedUser;

    public function __construct(
        Token $token,
        LoggedUser $loggedUser
    )
    {
        $this->token = $token;
        $this->loggedUser = $loggedUser;
    }

    public function getLoggedUser(): LoggedUser
    {
        return $this->loggedUser;
    }

    public function getToken(): Token
    {
        return $this->token;
    }

    public function toArray(): array
    {
        return [
            'token' => (string)$this->token,
            'user' => $this->loggedUser->toArray(),
        ];
    }
}