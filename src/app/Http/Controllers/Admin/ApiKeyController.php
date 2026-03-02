<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreApiKeyRequest;
use App\Http\Requests\Admin\UpdateApiKeyRequest;
use App\Models\ApiKey;
use App\Models\Plan;
use App\Services\ApiKeyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApiKeyController extends Controller
{
    public function __construct(public ApiKeyService $apiKeyService) {}

    public function index(Request $request): View
    {
        $query = ApiKey::with('plan');

        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('partner_name', 'like', "%{$request->search}%")
                    ->orWhere('partner_email', 'like', "%{$request->search}%");
            });
        }

        if ($request->has('plan_id') && $request->plan_id) {
            $query->where('plan_id', $request->plan_id);
        }

        $apiKeys = $query->orderBy('created_at', 'desc')->paginate(10);
        $plans = Plan::where('is_active', true)->get();

        return $this->view('admin.api-keys.index', [
            'apiKeys' => $apiKeys,
            'plans' => $plans,
        ]);
    }

    public function create(): View
    {
        $plans = Plan::where('is_active', true)->get();

        return $this->view('admin.api-keys.create', [
            'plans' => $plans,
        ]);
    }

    public function store(StoreApiKeyRequest $request): \Illuminate\Http\RedirectResponse
    {
        $apiKey = $this->apiKeyService->createApiKey($request->validated());

        return redirect()->route('admin.api-keys.index')->with('success', 'Clé API créée avec succès. Veuillez copier cette clé: '.$apiKey->key);
    }

    public function edit(ApiKey $apiKey): View
    {
        $plans = Plan::where('is_active', true)->get();

        return $this->view('admin.api-keys.edit', [
            'apiKey' => $apiKey,
            'plans' => $plans,
        ]);
    }

    public function show(ApiKey $apiKey): JsonResponse
    {
        $apiKey->load('plan');
        $stats = $this->apiKeyService->getStats($apiKey);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $apiKey->id,
                'partner_name' => $apiKey->partner_name,
                'partner_email' => $apiKey->partner_email,
                'key' => $apiKey->getKeyForDisplay(),
                'plan' => $apiKey->plan?->name,
                'permissions' => $apiKey->permissions,
                'is_active' => $apiKey->is_active,
                'expires_at' => $apiKey->expires_at?->toIso8601String(),
                'last_used_at' => $apiKey->last_used_at?->toIso8601String(),
                'created_at' => $apiKey->created_at->toIso8601String(),
            ],
            'stats' => $stats,
        ]);
    }

    public function update(UpdateApiKeyRequest $request, ApiKey $apiKey): \Illuminate\Http\RedirectResponse
    {
        $apiKey->update($request->validated());

        return redirect()->route('admin.api-keys.index')->with('success', 'Clé API mise à jour avec succès.');
    }

    public function destroy(ApiKey $apiKey): \Illuminate\Http\RedirectResponse
    {
        $apiKey->delete();

        return redirect()->route('admin.api-keys.index')->with('success', 'Clé API supprimée avec succès.');
    }

    public function regenerate(ApiKey $apiKey): \Illuminate\Http\RedirectResponse
    {
        $apiKey = $this->apiKeyService->regenerateApiKey($apiKey);

        return redirect()->route('admin.api-keys.index')->with('success', 'Clé API régénérée. Nouvelle clé: '.$apiKey->key);
    }

    public function toggle(ApiKey $apiKey): \Illuminate\Http\RedirectResponse
    {
        if ($apiKey->is_active) {
            $this->apiKeyService->revokeApiKey($apiKey);
            $message = 'Clé API révoquée.';
        } else {
            $this->apiKeyService->activateApiKey($apiKey);
            $message = 'Clé API activée.';
        }

        return redirect()->route('admin.api-keys.index')->with('success', $message);
    }
}
