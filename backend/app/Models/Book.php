<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'title',
        'author_id',
        'genre_id',
        'isbn',
        'total_copies',
        'available_copies'
    ];

    /**
     * Relación con el autor del libro
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * Relación con el género del libro
     */
    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }

    /**
     * Relación con los préstamos del libro
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}
