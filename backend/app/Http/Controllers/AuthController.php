<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController extends Controller
{
    private function generateJWT($user)
    {
        $payload = [
            'iss' => 'laravel-jwt',          // Issuer
            'sub' => $user->id,              // Subject (user ID)
            'iat' => time(),                 // Issued at
            'exp' => time() + 60 * 60        // Expiration time (1 hour)
        ];

        return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }

    private function decodeJWT($token)
    {
        try {
            return JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
        } catch (\Exception $e) {
            return null;
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'gender' => 'required|in:male,female'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender
        ]);

        $token = $this->generateJWT($user);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $this->generateJWT($user);

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function profile(Request $request)
    {
        $authHeader = $request->header('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['message' => 'Token not provided'], 401);
        }

        $token = substr($authHeader, 7);
        $decoded = $this->decodeJWT($token);

        if (!$decoded) {
            return response()->json(['message' => 'Invalid or expired token'], 401);
        }

        $user = User::find($decoded->sub);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        // JWT нельзя "удалить" — он будет действителен до истечения срока.
        // В продакшене можно использовать черный список (blacklist).
        return response()->json(['message' => 'Logout endpoint hit — JWT can’t be revoked on server without blacklist'], 200);
    }
}
