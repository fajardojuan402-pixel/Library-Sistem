<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    use HasFactory;

    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'name',
        'bio'
    ];

    /**
     * Relación con libros
     * Un autor puede tener muchos libros
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}

