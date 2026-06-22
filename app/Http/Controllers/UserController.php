<?php

namespace App\Http\Controllers;

use App\Core\Auth\PlainPassword;
use App\Core\User\Actions\{
    CreateUser,
    DeleteUser,
    ReadUser,
    SearchUser,
    UpdateUser,
};
use App\Core\User\{
    Email,
    UserID,
    UserRole,
};

use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function getAll()
    {
        try {
            $users = app(SearchUser::class)->execute();
            Log::debug('Retrieved users', $users);
            return response()
                ->json(
                    [
                        'message' => 'Users retrieved successfully',
                        'data' => array_map(fn($user) => $user->toArray(), $users)
                    ],
                    200
                );
        } catch (\Exception $e) {
            return response()
                ->json(
                    [
                        'message' => 'Failed to retrieve users',
                        'error' => $e->getMessage()
                    ],
                    500
                );
        }
    }

    public function getById(string $id)
    {
        try {
            $user = app(ReadUser::class)->execute(new UserID($id));
            
            if (!$user) {
                return response()
                    ->json(
                        [
                            'message' => 'User not found',
                        ],
                        404
                    );
            }

            return response()
                ->json(
                    [
                        'message' => 'User retrieved successfully',
                        'data' => $user->toArray()
                    ],
                    200
                );
        } catch (Exception $e) {
            return response()
                ->json(
                    [
                        'message' => 'Failed to retrieve user',
                        'error' => $e->getMessage()
                    ],
                    500
                );
        }
    }

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
        } catch (Exception $e) {
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
        } catch (Exception $e) {
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
        } catch (Exception $e) {
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
