<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'department',
        'postal_code',
    ];

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    public function formations(): HasMany
    {
        return $this->hasMany(Formation::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
