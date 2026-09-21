<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'genre',
        'description',
        'cover_image',
        'published_year',
        'price',
        'rating',
        'is_available',
    ];

    protected $casts = [
        'price' => 'float',
        'rating' => 'float',
        'published_year' => 'integer',
        'is_available' => 'boolean',
    ];

    /**
     * Scope filter for search query
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%")
                  ->orWhere('genre', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    /**
     * Scope filter for genre
     */
    public function scopeGenre($query, $genre)
    {
        if ($genre && $genre !== 'All') {
            return $query->where('genre', $genre);
        }
        return $query;
    }
}
