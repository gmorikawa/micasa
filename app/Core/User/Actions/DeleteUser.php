<?php

namespace App\Core\User\Actions;

use App\Core\Auth\PasswordHasher;
use App\Core\Auth\PlainPassword;
use App\Core\Common\Exceptions\ForbiddenActionException;
use App\Core\User\Exceptions\UserNotFoundException;
use App\Core\User\UserID;
use App\Core\User\UserRole;

use App\Models\UserModel;

class DeleteUser
{
    private readonly PasswordHasher $hasher;

    public function __construct(
        PasswordHasher $hasher,
    ) {
        $this->hasher = $hasher;
    }

    /**
     * Deletes a user with the given ID.
     * To delete the user, the user must provide their current password for confirmation.
     * 
     * @throws UserNotFoundException When the user with the given ID does not exist.
     * @throws ForbiddenActionException When the action is not allowed because of incorrect password confirmation or the user being deleted is an admin.
     */
    public function execute(
        UserID $id,
        PlainPassword $confirm,
    ): void
    {
        $model = UserModel::find($id);

        if (!$model) {
            throw new UserNotFoundException();
        }

        $user = $model->toEntity();

        if ($user->getRole() === UserRole::ADMIN) {
            throw new ForbiddenActionException();
        }

        if (!$this->hasher->verify($confirm, $user->getPassword())) {
            throw new ForbiddenActionException();
        }

        $model->delete();
    }

    /**
     * Deletes a user with the given ID without requiring password confirmation.
     * This method is intended for administrative use only.
     * 
     * @throws UserNotFoundException When the user with the given ID does not exist.
     * @throws ForbiddenActionException When the action is not allowed because the user being deleted is an admin.
     */
    public function executeAsAdmin(UserID $id): void
    {
        $model = UserModel::find($id);

        if (!$model) {
            throw new UserNotFoundException();
        }

        $user = $model->toEntity();

        if ($user->getRole() === UserRole::ADMIN) {
            throw new ForbiddenActionException();
        }

        $model->delete();
    }
}
