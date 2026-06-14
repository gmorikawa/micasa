<?php

namespace App\Core\User\Actions;

use App\Core\Auth\PasswordHasher;
use App\Core\Auth\PlainPassword;
use App\Core\Common\Exceptions\ForbiddenActionException;
use App\Core\User\Email;
use App\Core\User\Exceptions\DuplicatedEmailException;
use App\Core\User\Exceptions\UserNotFoundException;
use App\Core\User\User;
use App\Core\User\UserID;
use App\Core\User\UserRepository;

class UpdateUser
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
        UserID $id,
        Email $email,
        PlainPassword $confirm,
    ): User
    {
        $user = $this->repository->findById($id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        if (!$this->hasher->verify($confirm, $user->getPassword())) {
            throw new ForbiddenActionException();
        }

        $userWithEmail = $this->repository->findByEmail($email);

        if ($userWithEmail && !$userWithEmail->getId()->equals($id)) {
            throw new DuplicatedEmailException();
        }

        return $this
            ->repository
            ->save(new User(
                id: $user->getId(),
                email: $email,
                password: $user->getPassword(),
                role: $user->getRole()
            ));
    }
}
