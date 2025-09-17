<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LoanService;
use App\Models\Book;
use App\Models\Penalty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{
    protected LoanService $loanService;

    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    /**
     * Obtener los libros más prestados
     */
    public function topBooks(): JsonResponse
    {
        $raw = $this->loanService->topBooks();

        $out = $raw->map(function ($r) {
            $book = Book::find($r->book_id);
            return [
                'bookId' => $r->book_id,
                'title' => $book?->title,
                'loansCount' => $r->loans_count
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $out
        ]);
    }

    /**
     * Obtener estadísticas de disponibilidad de libros
     */
    public function availability(): JsonResponse
    {
        $totals = Book::selectRaw('SUM(total_copies) as total, SUM(available_copies) as available')->first();

        $percent = ($totals->total == 0) ? 0 : ($totals->available * 100 / $totals->total);

        return response()->json([
            'success' => true,
            'data' => [
                'totalCopies' => $totals->total,
                'availableCopies' => $totals->available,
                'percentAvailable' => round($percent, 2)
            ]
        ]);
    }

    /**
     * Obtener préstamos de los últimos 6 meses por mes
     */
    public function loansPerMonth(): JsonResponse
    {
        $rows = DB::table('loans')
            ->selectRaw("DATE_FORMAT(loan_date, '%Y-%m') as month, count(*) as total")
            ->where('loan_date', '>=', now()->subMonths(6)->toDateString())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $rows
        ]);
    }

    /**
     * Obtener estadísticas de penalizaciones
     */
    public function penalties(): JsonResponse
    {
        $totalAmount = Penalty::sum('amount');

        $penalizedUsers = User::whereHas('penalties', function ($q) {
            $q->whereNotNull('sent_at');
        })->get(['id', 'name', 'email']);

        return response()->json([
            'success' => true,
            'data' => [
                'totalAmount' => $totalAmount,
                'penalizedUsers' => $penalizedUsers
            ]
        ]);
    }
}
