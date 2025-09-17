<?php

namespace App\Repositories;

use App\Models\Loan;
use Illuminate\Database\Eloquent\Collection;

class LoanRepository
{
    /**
     * Crear un nuevo préstamo
     *
     * @param array $data
     * @return Loan
     */
    public function create(array $data): Loan
    {
        return Loan::create($data);
    }

    /**
     * Buscar un préstamo por ID con relaciones usuario y libro
     *
     * @param int $id
     * @return Loan
     */
    public function find(int $id): Loan
    {
        return Loan::with(['user', 'book'])->findOrFail($id);
    }

    /**
     * Obtener préstamos activos de un usuario
     *
     * @param int $userId
     * @return Collection
     */
    public function userActiveLoans(int $userId): Collection
    {
        return Loan::where('user_id', $userId)
            ->whereNull('return_date')
            ->get();
    }

    /**
     * Obtener los 5 libros más prestados
     *
     * @return Collection
     */
    public function topBooks(): Collection
    {
        return Loan::selectRaw('book_id, count(*) as loans_count')
            ->groupBy('book_id')
            ->orderByDesc('loans_count')
            ->limit(5)
            ->get();
    }
}
