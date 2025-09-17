<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    use HasFactory;

    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    /**
     * Relación con los préstamos realizados por el usuario
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Relación con las penalizaciones asociadas al usuario
     */
    public function penalties(): HasMany
    {
        return $this->hasMany(Penalty::class);
    }
}
