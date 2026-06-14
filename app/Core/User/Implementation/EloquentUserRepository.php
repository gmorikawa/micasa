<?php

namespace App\Core\User\Implementation;

use App\Core\Auth\HashedPassword;
use App\Core\User\Email;
use App\Core\User\User;
use App\Core\User\UserID;
use App\Core\User\UserRepository;
use App\Core\User\UserRole;
use App\Models\UserModel;

class EloquentUserRepository implements UserRepository
{
    public function findAll(): array
    {
        return UserModel::all()->toArray();
    }

    public function countAll(): int
    {
        return UserModel::count();
    }

    public function findById(UserID $id): ?User
    {
        $model = UserModel::find($id);

        return $model
            ? new User(
                new UserID($model->id),
                new Email($model->email),
                new HashedPassword($model->password),
                UserRole::from($model->role)
            )
            : null;
    }

    public function findByEmail(Email $email): ?User
    {
        $model = UserModel::where('email', $email)->first();

        return $model
            ? new User(
                new UserID($model->id),
                new Email($model->email),
                new HashedPassword($model->password),
                UserRole::from($model->role)
            )
            : null;
    }

    public function save(User $user): User
    {
        $model = $user->getId()->isNull()
            ? new UserModel()
            : UserModel::find($user->getId());
        
        $model->email = $user->getEmail();
        $model->password = $user->getPassword();
        $model->role = $user->getRole()->value;
        $model->save();

        return $model->toEntity();
    }

    public function delete(UserID $id): void
    {
        UserModel::destroy($id);
    }
}