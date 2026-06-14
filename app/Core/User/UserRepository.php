<?php

namespace App\Core\User;

interface UserRepository
{
    public function findAll(): array;

    public function countAll(): int;

    public function findById(UserID $id): ?User;

    public function findByEmail(Email $email): ?User;

    public function save(User $user): User;

    public function delete(UserID $id): void;
}