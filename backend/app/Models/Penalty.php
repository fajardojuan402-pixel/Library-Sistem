<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penalty extends Model
{
    use HasFactory;

    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'loan_id',
        'user_id',
        'amount',
        'reason',
        'sent_at',
    ];

    /**
     * Relación con el préstamo asociado
     */
    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    /**
     * Relación con el usuario penalizado
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

