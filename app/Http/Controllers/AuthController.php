<?php

namespace App\Http\Controllers;

use App\Core\Auth\Credentials;
use App\Core\Auth\Login;
use App\Core\Auth\Logout;
use App\Core\Auth\PlainPassword;
use App\Core\Auth\RegisterAdmin;
use App\Core\Auth\Token;
use App\Core\User\Email;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function registerAdmin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        try {
            $admin = app(RegisterAdmin::class)
                ->execute(
                    new Email($request->input('email')),
                    new PlainPassword($request->input('password'))
                );

            return response()
                ->json(
                    [
                        'message' => 'Admin registration successful',
                        'data' => $admin->toArray()
                    ],
                    201
                );
        } catch (\Exception $e) {
            return response()
                ->json(
                    [
                        'message' => 'Admin registration failed',
                        'error' => $e->getMessage()
                    ],
                    400
                );
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        try {
            $session = app(Login::class)
                ->execute(
                    new Credentials(
                        new Email($request->input('email')),
                        new PlainPassword($request->input('password'))
                    )
                );

            return response()
                ->json(
                    [
                        'message' => 'Login successful',
                        'data' => $session->toArray()
                    ],
                    200
                );
        } catch (\Exception $e) {
            return response()
                ->json(
                    [
                        'message' => 'Login failed',
                        'error' => $e->getMessage()
                    ],
                    401
                );
        }
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()
                ->json(
                    [
                        'message' => 'Logout failed: user not authenticated',
                    ],
                    401
                );
        }

        try {
            app(Logout::class)->execute(new Token($token));

            return response()
                ->json(
                    [
                        'message' => 'Logout successful',
                    ],
                    200
                );
        } catch (\Exception $e) {
            return response()
                ->json(
                    [
                        'message' => 'Logout failed',
                        'error' => $e->getMessage()
                    ],
                    400
                );
        }
    }
}