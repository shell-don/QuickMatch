<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Services\SecurityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(public SecurityLogger $securityLogger) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
        ]);

        $token = $user->createToken($request->validated('device_name', 'register-token'))->plainTextToken;

        $this->securityLogger->logLoginSuccess($user->id, $request->ip());

        return response()->json([
            'message' => 'Utilisateur créé avec succès.',
            'user' => new UserResource($user),
            'token' => $token,
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->validated('email'))->first();

        if (! $user || ! Hash::check($request->validated('password'), $user->password)) {
            $this->securityLogger->logLoginFailed($request->validated('email'), $request->ip());

            throw ValidationException::withMessages([
                'email' => ['Identifiants incorrects.'],
            ]);
        }

        $token = $user->createToken($request->validated('device_name', 'login-token'))->plainTextToken;

        $this->securityLogger->logLoginSuccess($user->id, $request->ip());

        return response()->json([
            'message' => 'Connexion réussie.',
            'user' => new UserResource($user),
            'token' => $token,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $ipAddress = $request->ip();

        $request->user()->currentAccessToken()?->delete();

        $this->securityLogger->logLogout($userId, $ipAddress);

        return response()->json([
            'message' => 'Déconnexion réussie.',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => new UserResource($request->user()),
        ]);
    }
}
