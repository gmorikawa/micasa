<?php

namespace App\Core\Auth\Actions;

use App\Core\Auth\Exceptions\InvalidCredentialsException;
use App\Core\Auth\PasswordHasher;
use App\Core\Auth\PlainPassword;
use App\Core\User\Actions\SearchUser;
use App\Core\User\Admin;
use App\Core\User\Email;
use App\Core\User\User;
use App\Core\User\UserID;

use App\Models\UserModel;

class RegisterAdmin
{
    private readonly PasswordHasher $passwordHasher;

    public function __construct(
        PasswordHasher $passwordHasher,
    )
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Registers an admin user with the given email and password.
     * 
     * @throws InvalidCredentialsException When an admin user already exists.
     */
    public function execute(Email $email, PlainPassword $password): User
    {
        $totalUsers = app(SearchUser::class)->count();

        if ($totalUsers > 0) {
            throw new InvalidCredentialsException('Admin user already exists.');
        }

        $hashedPassword = $this->passwordHasher->hash($password);
        $admin = new Admin(
            id: new UserID(null),
            email: new Email($email),
            password: $hashedPassword,
        );

        $model = new UserModel();
        $model->email = $admin->getEmail();
        $model->password = $admin->getPassword();
        $model->role = $admin->getRole()->value;
        $model->save();

        return $model->toEntity();
    }
}
