<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use App\Models\User;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Token not provided'], 401);
        }

        $secret = (string) env('JWT_SECRET');

        if (empty($secret)) {
            return response()->json(['message' => 'JWT secret is empty'], 500);
        }

        if (!$secret || !is_string($secret)) {
            return response()->json(['message' => 'JWT secret is not configured'], 500);
        }

        try {
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));

            if (!isset($decoded->sub)) {
                return response()->json(['message' => 'Invalid token payload'], 401);
            }

            $user = User::find($decoded->sub);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            auth()->setUser($user);
            return $next($request);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Token verification failed',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 401);
        }
    }
}

