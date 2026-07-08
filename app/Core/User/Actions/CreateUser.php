<?php

namespace App\Core\User\Actions;

use App\Core\Auth\PasswordHasher;
use App\Core\Auth\PlainPassword;
use App\Core\User\Email;
use App\Core\User\Exceptions\DuplicatedEmailException;
use App\Core\User\User;
use App\Core\User\UserID;
use App\Core\User\UserRole;

use App\Models\UserModel;

class CreateUser
{
    private readonly PasswordHasher $hasher;

    public function __construct(
        PasswordHasher $hasher,
    ) {
        $this->hasher = $hasher;
    }

    public function execute(
        Email $email,
        PlainPassword $plainPassword,
        UserRole $role
    ): User
    {
        $hashedPassword = $this->hasher->hash($plainPassword);

        $userWithEmail = UserModel::where('email', $email)->first();

        if ($userWithEmail) {
            throw new DuplicatedEmailException();
        }

        $entity = new User(
            id: new UserID(null),
            email: $email,
            password: $hashedPassword,
            role: $role
        );

        $model = new UserModel();
        $model->email = $entity->getEmail();
        $model->password = $entity->getPassword();
        $model->role = $entity->getRole()->value;
        $model->save();

        return $model->toEntity();
    }
}