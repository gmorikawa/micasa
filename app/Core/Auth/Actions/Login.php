<?php

namespace App\Core\Auth\Actions;

use App\Core\Auth\Credentials;
use App\Core\Auth\Exceptions\InvalidCredentialsException;
use App\Core\Auth\PasswordHasher;
use App\Core\Auth\Token;
use App\Core\Cache\Cache;
use App\Core\User\Actions\SearchUser;
use App\Core\User\LoggedUser;
use App\Core\User\UserSession;

use Illuminate\Support\Str;

class Login
{
    private readonly Cache $cache;
    private readonly PasswordHasher $passwordHasher;

    public function __construct(
        Cache $cache,
        PasswordHasher $passwordHasher,
    )
    {
        $this->cache = $cache;
        $this->passwordHasher = $passwordHasher;
    }

    public function execute(Credentials $credentials): UserSession
    {
        $user = app(SearchUser::class)->byEmail($credentials->getEmail());

        if (!$user || !$this->passwordHasher->verify($credentials->getPassword(), $user->getPassword())) {
            throw new InvalidCredentialsException();
        }

        $loggedUser = LoggedUser::fromUser($user);
        $token = new Token(Str::random(60));

        // Cache for 7 days
        $this->cache->set((string)$token, $loggedUser->toJson(), 7 * 24 * 3600);

        return new UserSession($token, $loggedUser);
    }
}