<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TokenController extends Controller
{
    public function createToken(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 403);
        }

        $token = $user->createToken('api-token');

        return response()->json([
            'token' => $token->plainTextToken
        ]);
    }
}
