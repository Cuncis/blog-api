<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => bcrypt($request->validated('password')),
        ]);

        $token = $user->createToken('api-token', ['posts.read', 'posts.write', 'posts.delete'])->plainTextToken;

        return $this->success([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 'User registered successfully', 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->validated('email'))->first();

        if (!$user || !Hash::check($request->validated('password'), $user->password)) {
            return $this->error('Invalid credentials', 401);
        }

        $token = $user->createToken('api-token', ['posts.read', 'posts.write', 'posts.delete'])->plainTextToken;

        return $this->success([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 'User logged in successfully');
    }

    public function issueReadOnlyToken(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return $this->error('User not authenticated', 401);
        }

        $token = $user->createToken('read-only-token', ['posts.read'])->plainTextToken;

        return $this->success([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 'Read-only token issued successfully', 201);
    }

    public function listTokens(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return $this->error('User not authenticated', 401);
        }

        $tokens = $user->tokens()->get(['id', 'name', 'abilities', 'last_used_at', 'created_at']);

        return $this->success($tokens, 'User tokens retrieved successfully');
    }

    public function revokeToken(Request $request, string $tokenId): JsonResponse
    {
        $deleted = $request->user()->tokens()->where('id', $tokenId)->delete();

        if (!$deleted) {
            return $this->error('Token not found', 404);
        }

        return $this->success(null, 'Token revoked successfully');
    }

    public function logout(Request $request): JsonResponse
    {
        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();

        // Return a response
        return $this->success(null, 'User logged out successfully');
    }

    public function logoutAll(Request $request): JsonResponse
    {
        // Revoke all tokens for the authenticated user
        $request->user()->tokens()->delete();

        // Return a response
        return $this->success(null, 'User logged out from all devices successfully');
    }
}
