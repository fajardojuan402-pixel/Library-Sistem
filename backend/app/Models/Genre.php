<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Genre extends Model
{
    use HasFactory;

    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'name'
    ];

    /**
     * Relación con libros
     * Un género puede tener muchos libros
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
