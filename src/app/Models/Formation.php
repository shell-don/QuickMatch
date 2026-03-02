<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Formation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'level',
        'duration',
        'school',
        'city',
        'url',
        'type',
        'region_id',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'formation_skills');
    }

    public function professions(): BelongsToMany
    {
        return $this->belongsToMany(Profession::class, 'profession_formations');
    }
}
