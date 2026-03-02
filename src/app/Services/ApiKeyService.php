<?php

namespace App\Services;

use App\Models\ApiKey;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ApiKeyService
{
    public function createApiKey(array $data): ApiKey
    {
        $key = ApiKey::create([
            'key' => Hash::make(ApiKey::generateKey()),
            'prefix' => ApiKey::generatePrefix(),
            'partner_name' => $data['partner_name'],
            'partner_email' => $data['partner_email'] ?? null,
            'plan_id' => $data['plan_id'],
            'permissions' => $data['permissions'] ?? ['users:read'],
            'expires_at' => $data['expires_at'] ?? null,
            'ip_whitelist' => $data['ip_whitelist'] ?? null,
            'is_active' => true,
        ]);

        if (! empty($data['send_email']) && ! empty($data['partner_email'])) {
            $this->sendApiKeyEmail($key);
        }

        return $key;
    }

    public function validateApiKey(string $rawKey): ?ApiKey
    {
        $apiKeys = ApiKey::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->get();

        foreach ($apiKeys as $apiKey) {
            if (Hash::check($rawKey, $apiKey->key)) {
                return $apiKey;
            }
        }

        return null;
    }

    public function regenerateApiKey(ApiKey $apiKey): ApiKey
    {
        $oldEmail = $apiKey->partner_email;
        $apiKey->regenerate();

        if ($oldEmail) {
            $this->sendApiKeyEmail($apiKey, true);
        }

        return $apiKey;
    }

    public function revokeApiKey(ApiKey $apiKey): void
    {
        $apiKey->update(['is_active' => false]);
    }

    public function activateApiKey(ApiKey $apiKey): void
    {
        $apiKey->update(['is_active' => true]);
    }

    public function getStats(ApiKey $apiKey): array
    {
        $totalRequests = $apiKey->usages()->count();
        $todayRequests = $apiKey->getTodayRequestCount();
        $lastUsed = $apiKey->last_used_at;

        $dailyLimit = $apiKey->plan?->max_requests_per_day;
        $remaining = $dailyLimit ? max(0, $dailyLimit - $todayRequests) : null;

        return [
            'total_requests' => $totalRequests,
            'today_requests' => $todayRequests,
            'daily_limit' => $dailyLimit,
            'remaining' => $remaining,
            'last_used_at' => $lastUsed,
        ];
    }

    protected function sendApiKeyEmail(ApiKey $apiKey, bool $isRegeneration = false): void
    {
        // TODO: Implement email sending
        // For now, this is a placeholder
        // Mail::to($apiKey->partner_email)->send(new ApiKeyMail($apiKey, $isRegeneration));
    }
}
