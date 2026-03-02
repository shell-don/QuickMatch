<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiKeyUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_key_id',
        'endpoint',
        'method',
        'status_code',
        'ip_address',
        'response_time',
    ];

    protected $casts = [
        'status_code' => 'integer',
        'response_time' => 'integer',
    ];

    public function apiKey(): BelongsTo
    {
        return $this->belongsTo(ApiKey::class);
    }
}
