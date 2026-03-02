<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'max_users',
        'price',
        'max_requests_per_day',
        'is_active',
        'description',
    ];

    protected $casts = [
        'max_users' => 'integer',
        'price' => 'decimal:2',
        'max_requests_per_day' => 'integer',
        'is_active' => 'boolean',
    ];

    public function apiKeys(): HasMany
    {
        return $this->hasMany(ApiKey::class);
    }

    public function isUnlimited(): bool
    {
        return is_null($this->max_users);
    }

    public function canAccessUsers(int $userCount): bool
    {
        if ($this->isUnlimited()) {
            return true;
        }

        return $userCount <= $this->max_users;
    }
}
