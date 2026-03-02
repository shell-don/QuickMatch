<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\UserCollection;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function users(Request $request): JsonResponse
    {
        $apiKey = $request->user();

        if (! $apiKey->hasPermission('users:read')) {
            return response()->json([
                'success' => false,
                'message' => 'Permission denied.',
            ], 403);
        }

        $query = User::query();

        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $sortColumn = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortColumn, $sortDirection);

        $perPage = min($request->get('per_page', 15), 100);

        $users = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => new UserCollection($users),
        ]);
    }

    public function user(Request $request, User $user): JsonResponse
    {
        $apiKey = $request->user();

        if (! $apiKey->hasPermission('users:read')) {
            return response()->json([
                'success' => false,
                'message' => 'Permission denied.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => new UserResource($user),
        ]);
    }

    public function stats(Request $request): JsonResponse
    {
        $apiKey = $request->user();

        if (! $apiKey->hasPermission('stats:read')) {
            return response()->json([
                'success' => false,
                'message' => 'Permission denied.',
            ], 403);
        }

        $totalUsers = User::count();
        $totalAdmins = User::role('admin')->count();
        $userCount = User::count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_users' => $totalUsers,
                'total_admins' => $totalAdmins,
                'plan_limit' => $apiKey->plan?->max_users,
                'remaining_users' => $apiKey->plan?->isUnlimited()
                    ? null
                    : max(0, $apiKey->plan->max_users - $userCount),
            ],
        ]);
    }
}
