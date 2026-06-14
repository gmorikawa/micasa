<?php

namespace App\Http\Controllers;

use App\Core\Auth\PlainPassword;
use App\Core\User\Actions\CreateUser;
use App\Core\User\Actions\DeleteUser;
use App\Core\User\Actions\UpdateUser;
use App\Core\User\Email;
use App\Core\User\UserID;
use App\Core\User\UserRole;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function create(Request $request)
    {
        $roles = UserRole::ADMIN->value . ',' . UserRole::MEMBER->value;
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string',
            'role' => "required|string|in:$roles",
        ]);

        try {
            $user = app(CreateUser::class)
                ->execute(
                    new Email($request->input('email')),
                    new PlainPassword($request->input('password')),
                    UserRole::from($request->input('role')),
                );
            
            return response()
                ->json(
                    [
                        'message' => 'User created successfully',
                        'data' => $user->toArray()
                    ],
                    201
                );
        } catch (\Exception $e) {
            return response()
                ->json(
                    [
                        'message' => 'Failed to create user',
                        'error' => $e->getMessage()
                    ],
                    500
                );
        }
    }

    public function update(Request $request, string $id)
    {
        $roles = UserRole::ADMIN->value . ',' . UserRole::MEMBER->value;
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'required|string',
            'role' => "required|string|in:$roles",
        ]);

        try {
            $user = app(UpdateUser::class)
                ->execute(
                    new UserID($id),
                    new Email($request->input('email')),
                    new PlainPassword($request->input('password')),
                );
            
            return response()
                ->json(
                    [
                        'message' => 'User updated successfully',
                        'data' => $user->toArray()
                    ],
                    200
                );
        } catch (\Exception $e) {
            return response()
                ->json(
                    [
                        'message' => 'Failed to update user',
                        'error' => $e->getMessage()
                    ],
                    500
                );
        }
    }

    public function delete(string $id)
    {
        try {
            app(DeleteUser::class)->executeAsAdmin(new UserID($id));
            
            return response()
                ->json(
                    [
                        'message' => 'User deleted successfully',
                    ],
                    200
                );
        } catch (\Exception $e) {
            return response()
                ->json(
                    [
                        'message' => 'Failed to delete user',
                        'error' => $e->getMessage()
                    ],
                    500
                );
        }
    }
}