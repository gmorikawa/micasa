<?php

namespace App\Core\Auth\Actions;

use App\Core\Auth\Exceptions\InvalidCredentialsException;
use App\Core\Auth\PasswordHasher;
use App\Core\Auth\PlainPassword;
use App\Core\User\Admin;
use App\Core\User\Email;
use App\Core\User\User;
use App\Core\User\UserID;
use App\Core\User\UserRepository;

class RegisterAdmin
{
    private readonly PasswordHasher $passwordHasher;
    private readonly UserRepository $userRepository;

    public function __construct(
        PasswordHasher $passwordHasher,
        UserRepository $userRepository
    )
    {
        $this->passwordHasher = $passwordHasher;
        $this->userRepository = $userRepository;
    }

    public function execute(Email $email, PlainPassword $password): User
    {
        $totalUsers = $this->userRepository->countAll();

        if ($totalUsers > 0) {
            throw new InvalidCredentialsException('Admin user already exists.');
        }

        $hashedPassword = $this->passwordHasher->hash($password);
        $admin = new Admin(
            id: new UserID(null),
            email: new Email($email),
            password: $hashedPassword,
        );
        return $this->userRepository->save($admin);
    }
}
