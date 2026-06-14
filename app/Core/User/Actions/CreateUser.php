<?php

namespace App\Core\User\Actions;

use App\Core\Auth\PasswordHasher;
use App\Core\Auth\PlainPassword;
use App\Core\User\Email;
use App\Core\User\Exceptions\DuplicatedEmailException;
use App\Core\User\User;
use App\Core\User\UserID;
use App\Core\User\UserRepository;
use App\Core\User\UserRole;

class CreateUser
{
    private readonly PasswordHasher $hasher;
    private readonly UserRepository $repository;

    public function __construct(
        PasswordHasher $hasher,
        UserRepository $repository
    ) {
        $this->hasher = $hasher;
        $this->repository = $repository;
    }

    public function execute(
        Email $email,
        PlainPassword $plainPassword,
        UserRole $role
    ): User
    {
        $hashedPassword = $this->hasher->hash($plainPassword);

        $userWithEmail = $this->repository->findByEmail($email);

        if ($userWithEmail) {
            throw new DuplicatedEmailException();
        }

        $entity = new User(
            id: new UserID(null),
            email: $email,
            password: $hashedPassword,
            role: $role
        );

        return $this->repository->save($entity);
    }
}