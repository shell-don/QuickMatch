<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'summary',
        'content',
        'source',
        'source_url',
        'category',
        'published_at',
        'ai_summary',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'date',
        ];
    }
}
