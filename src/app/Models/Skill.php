<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
    ];

    public function offers(): BelongsToMany
    {
        return $this->belongsToMany(Offer::class, 'offer_skills');
    }

    public function professions(): BelongsToMany
    {
        return $this->belongsToMany(Profession::class, 'profession_skills');
    }

    public function formations(): BelongsToMany
    {
        return $this->belongsToMany(Formation::class, 'formation_skills');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_skills');
    }
}
