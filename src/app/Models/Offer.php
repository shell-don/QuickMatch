<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'requirements',
        'type',
        'status',
        'salary_min',
        'salary_max',
        'duration',
        'start_date',
        'end_date',
        'source_url',
        'source',
        'is_remote',
        'company_id',
        'region_id',
        'profession_id',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'salary_min' => 'decimal:2',
            'salary_max' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
            'is_remote' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function profession(): BelongsTo
    {
        return $this->belongsTo(Profession::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'offer_skills');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(UserApplication::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
