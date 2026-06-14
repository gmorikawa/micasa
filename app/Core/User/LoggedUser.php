<?php

namespace App\Core\User;

class LoggedUser
{
    private UserID $id;
    private Email $email;
    private UserRole $role;

    public function __construct(UserID $id, Email $email, UserRole $role)
    {
        $this->id = $id;
        $this->email = $email;
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

    public function getRole(): UserRole
    {
        return $this->role;
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function toArray(): array
    {
        return [
            'id' => (string)$this->id,
            'email' => (string)$this->email,
            'role' => $this->role->value,
        ];
    }

    public static function fromJson(string $data): self
    {
        $decoded = json_decode($data, true);
        return new self(
            new UserID($decoded['id']),
            new Email($decoded['email']),
            UserRole::from($decoded['role'])
        );
    }

    public static function fromUser(User $user): self
    {
        return new self($user->getId(), $user->getEmail(), $user->getRole());
    } 
}