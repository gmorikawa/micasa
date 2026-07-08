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

use App\Models\UserModel;

class UpdateUser
{
    private readonly PasswordHasher $hasher;

    public function __construct(
        PasswordHasher $hasher,
    ) {
        $this->hasher = $hasher;
    }

    /**
     * Updates the email of a user with the given ID.
     * To update the email, the user must provide their current password for confirmation.
     * 
     * @throws UserNotFoundException When the user with the given ID does not exist.
     * @throws ForbiddenActionException When the action is not allowed because of incorrect password confirmation.
     * @throws DuplicatedEmailException When the email is already used by another user.
     */
    public function execute(
        UserID $id,
        Email $email,
        PlainPassword $confirm,
    ): User
    {
        $model = UserModel::find($id);

        if (!$model) {
            throw new UserNotFoundException();
        }

        $user = $model->toEntity();

        if (!$this->hasher->verify($confirm, $user->getPassword())) {
            throw new ForbiddenActionException();
        }

        $userWithEmail = UserModel::where('email', $email)->first()?->toEntity();

        if ($userWithEmail && !$userWithEmail->getId()->equals($id)) {
            throw new DuplicatedEmailException();
        }

        $user->setEmail($email);
        $model->email = $user->getEmail();
        $model->save();

        return $model->toEntity();
    }
}
