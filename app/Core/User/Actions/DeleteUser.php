<?php

namespace App\Core\User\Actions;

use App\Core\Auth\PasswordHasher;
use App\Core\Auth\PlainPassword;
use App\Core\Common\Exceptions\ForbiddenActionException;
use App\Core\User\Exceptions\UserNotFoundException;
use App\Core\User\UserID;
use App\Core\User\UserRepository;
use App\Core\User\UserRole;

class DeleteUser
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
        PlainPassword $confirm,
    ): void
    {
        $user = $this->repository->findById($id);

        if ($user->getRole() === UserRole::ADMIN) {
            throw new ForbiddenActionException();
        }

        if (!$this->hasher->verify($confirm, $user->getPassword())) {
            throw new ForbiddenActionException();
        }

        if (!$user) {
            throw new UserNotFoundException();
        }

        $this->repository->delete($id);
    }

    public function executeAsAdmin(
        UserID $id,
    ): void
    {
        $user = $this->repository->findById($id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        $this->repository->delete($id);
    }
}
