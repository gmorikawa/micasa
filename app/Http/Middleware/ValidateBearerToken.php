<?php

namespace App\Http\Middleware;

use App\Core\Cache\Cache;
use App\Core\User\LoggedUser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateBearerToken
{
    public function __construct(
        private readonly Cache $cache
    ) { }

    public function handle(Request $request, Closure $next): Response
    {
        $authorizationHeader = $request->header('Authorization');

        if (!is_string($authorizationHeader) || !preg_match('/^Bearer\s+\S+$/i', $authorizationHeader)) {
            return response()->json([
                'message' => 'Unauthorized: missing or invalid Bearer token.',
            ], 401);
        }

        $token = $request->bearerToken();
        $cachedLoggedUser = $token ? $this->cache->get($token) : null;

        if (!$token || !is_string($cachedLoggedUser)) {
            return response()->json([
                'message' => 'Unauthorized: token is invalid or expired.',
            ], 401);
        }

        try {
            $loggedUser = LoggedUser::fromJson($cachedLoggedUser);
        } catch (\Throwable) {
            return response()->json([
                'message' => 'Unauthorized: token payload is invalid.',
            ], 401);
        }

        $request->attributes->set('loggedUser', $loggedUser);

        return $next($request);
    }
}