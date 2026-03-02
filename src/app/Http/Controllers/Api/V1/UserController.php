<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\Api\V1\UserCollection;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): UserCollection
    {
        $query = User::with('roles');

        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->has('role') && $request->role) {
            $query->whereHas('roles', fn ($q) => $q->where('name', $request->role));
        }

        $sortColumn = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortColumn, $sortDirection);

        $perPage = (int) $request->input('per_page', 15);
        $perPage = max(1, min($perPage, 100));

        return new UserCollection($query->paginate($perPage));
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
        ]);

        if ($request->has('roles')) {
            $user->assignRole($request->validated('roles'));
        }

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur créé avec succès.',
            'data' => new UserResource($user->load('roles')),
        ], 201);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new UserResource($user->load(['roles', 'permissions'])),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $user->update($request->only(['name', 'email']));

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->validated('password'))]);
        }

        if ($request->has('roles')) {
            $user->syncRoles($request->validated('roles', []));
        }

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur mis à jour avec succès.',
            'data' => new UserResource($user->load('roles')),
        ]);
    }

    public function destroy(User $user): JsonResponse
    {
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas supprimer votre propre compte.',
            ], 422);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur supprimé avec succès.',
        ]);
    }

    public function roles(): JsonResponse
    {
        $roles = Role::with('permissions')->get()->map(fn ($role) => [
            'name' => $role->name,
            'permissions' => $role->permissions->pluck('name'),
        ]);

        return response()->json([
            'success' => true,
            'data' => $roles,
        ]);
    }

    public function permissions(Request $request): JsonResponse
    {
        $user = $request->user();

        $permissions = $user->getAllPermissions()->pluck('name');

        return response()->json([
            'success' => true,
            'data' => [
                'roles' => $user->getRoleNames(),
                'permissions' => $permissions,
            ],
        ]);
    }
}
