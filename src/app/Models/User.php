<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'region_id',
        'phone',
        'bio',
        'avatar_path',
        'is_company',
        'company_id',
        'search_type',
        'availability_date',
        'cv_path',
        'driving_license',
        'is_available',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_company' => 'boolean',
            'driving_license' => 'boolean',
            'is_available' => 'boolean',
            'availability_date' => 'date',
        ];
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'user_skills');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(UserApplication::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class, 'created_by');
    }

    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class);
    }

    public function isStudent(): bool
    {
        return ! $this->is_company && $this->hasRole('user');
    }

    public function isCompany(): bool
    {
        return $this->is_company && $this->hasRole('entreprise');
    }
}
