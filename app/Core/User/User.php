<?php

namespace App\Core\User;

use App\Core\Auth\HashedPassword;
use App\Core\User\UserRole;

class User
{
    private UserID $id;
    private Email $email;
    private HashedPassword $password;
    private UserRole $role;

    public function __construct(
        UserID $id,
        Email $email,
        HashedPassword $password,
        UserRole $role
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    public function getId(): UserID
    {
        return $this->id;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPassword(): HashedPassword
    {
        return $this->password;
    }

    public function getRole(): UserRole
    {
        return $this->role;
    }

    public function setEmail(Email $email): void
    {
        $this->email = $email;
    }

    public function setPassword(HashedPassword $password): void
    {
        $this->password = $password;
    }

    public function toArray(): array
    {
        return [
            'id' => (string)$this->id,
            'email' => (string)$this->email,
            'role' => $this->role->value,
        ];
    }
}
