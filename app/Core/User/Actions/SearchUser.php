<?php

namespace App\Core\User\Actions;

use App\Core\User\User;
use App\Core\User\UserID;
use App\Models\UserModel;

use Illuminate\Support\Collection;

class SearchUser
{
    /**
     * Get all users.
     * 
     * @return Collection<User> A Laravel collection of User entities.
     */
    public function all(): Collection
    {
        return UserModel::all()->map(fn($model) => $model->toEntity());
    }

    /**
     * Count all users.
     * 
     * @return int The total number of users.
     */
    public function count(): int
    {
        return UserModel::count();
    }

    /**
     * Find a user by their ID.
     * 
     * @param UserID $id The ID of the user to find.
     * @return User|null The User entity if found, or null if not found.
     */
    public function byId(UserID $id): ?User
    {
        $model = UserModel::find($id);

        return $model ? $model->toEntity() : null;
    }

    /**
     * Find a user by their email.
     * 
     * @param string $email The email of the user to find.
     * @return User|null The User entity if found, or null if not found.
     */
    public function byEmail(string $email): ?User
    {
        $model = UserModel::where('email', $email)->first();

        return $model ? $model->toEntity() : null;
    }
}
