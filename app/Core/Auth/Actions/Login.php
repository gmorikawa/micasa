<?php

namespace App\Core\Auth;

use App\Core\Auth\Exceptions\InvalidCredentialsException;
use App\Core\Cache;
use App\Core\User\LoggedUser;
use App\Core\User\UserRepository;
use App\Core\User\UserSession;
use Illuminate\Support\Str;

class Login
{
    private readonly Cache $cache;
    private readonly PasswordHasher $passwordHasher;
    private readonly UserRepository $userRepository;

    public function __construct(
        Cache $cache,
        PasswordHasher $passwordHasher,
        UserRepository $userRepository
    )
    {
        $this->cache = $cache;
        $this->passwordHasher = $passwordHasher;
        $this->userRepository = $userRepository;
    }

    public function execute(Credentials $credentials): UserSession
    {
        $user = $this->userRepository->findByEmail($credentials->getEmail());

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