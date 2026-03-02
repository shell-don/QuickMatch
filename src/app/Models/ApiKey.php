<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'prefix',
        'partner_name',
        'partner_email',
        'plan_id',
        'permissions',
        'is_active',
        'expires_at',
        'last_used_at',
        'ip_whitelist',
    ];

    protected $hidden = [
        'key',
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
        'last_used_at' => 'datetime',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function usages(): HasMany
    {
        return $this->hasMany(ApiKeyUsage::class);
    }

    public static function generateKey(): string
    {
        return 'sk_'.Str::random(48);
    }

    public static function generatePrefix(): string
    {
        return 'sk_live_'.Str::random(8);
    }

    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions ?? []);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return $this->is_active && ! $this->isExpired();
    }

    public function recordUsage(string $endpoint, string $method, int $statusCode, ?string $ip = null, ?int $responseTime = null): void
    {
        $this->usages()->create([
            'endpoint' => $endpoint,
            'method' => $method,
            'status_code' => $statusCode,
            'ip_address' => $ip,
            'response_time' => $responseTime,
        ]);

        $this->update(['last_used_at' => now()]);
    }

    public function getTodayRequestCount(): int
    {
        return $this->usages()
            ->whereDate('created_at', today())
            ->count();
    }

    public function canMakeRequest(): bool
    {
        if (! $this->isValid()) {
            return false;
        }

        $dailyLimit = $this->plan?->max_requests_per_day;

        if (is_null($dailyLimit)) {
            return true;
        }

        return $this->getTodayRequestCount() < $dailyLimit;
    }

    public function regenerate(): self
    {
        $this->update([
            'key' => self::generateKey(),
            'prefix' => self::generatePrefix(),
        ]);

        return $this;
    }

    public function getKeyForDisplay(): string
    {
        return $this->prefix.'...'.substr($this->key, -4);
    }
}
