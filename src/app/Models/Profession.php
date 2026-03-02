<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profession extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'rome_code',
    ];

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'profession_skills');
    }

    public function formations(): BelongsToMany
    {
        return $this->belongsToMany(Formation::class, 'profession_formations');
    }
}
