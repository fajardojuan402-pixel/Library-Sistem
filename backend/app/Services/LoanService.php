<?php

namespace App\Services;

use App\Repositories\LoanRepository;
use App\Repositories\BookRepository;
use App\Mail\LoanPenaltyMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Penalty;
use App\Models\Loan;
use App\Models\Book;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class LoanService
{
    protected LoanRepository $loans;
    protected BookRepository $books;

    public function __construct(LoanRepository $loans, BookRepository $books)
    {
        $this->loans = $loans;
        $this->books = $books;
    }

    /**
     * Listar todos los préstamos con sus relaciones
     *
     * @param array $filters
     * @return Collection
     */
    public function list(array $filters = []): Collection
    {
        return Loan::with(['user', 'book'])->get();
    }

    /**
     * Crear un nuevo préstamo
     *
     * @param int $userId
     * @param int $bookId
     * @return Loan
     * @throws \Exception
     */
    public function createLoan(int $userId, int $bookId): Loan
    {
        return DB::transaction(function () use ($userId, $bookId) {
            $book = Book::findOrFail($bookId);

            if (($book->available_copies ?? 0) < 1) {
                throw new \Exception('No copies available');
            }

            $active = Loan::where('user_id', $userId)->whereNull('return_date')->count();
            if ($active >= 5) {
                throw new \Exception('User reached max active loans');
            }

            $book->available_copies--;
            $book->save();

            $loan = Loan::create([
                'user_id' => $userId,
                'book_id' => $bookId,
                'loan_date' => Carbon::now()->toDateString(),
                'due_date' => Carbon::now()->addDays(14)->toDateString(),
                'status' => 'on_loan'
            ]);

            return $loan->load(['user', 'book']);
        });
    }

    /**
     * Devolver un préstamo
     *
     * @param int $loanId
     * @return Loan
     * @throws \Exception
     */
    public function returnLoan(int $loanId): Loan
    {
        return DB::transaction(function () use ($loanId) {
            $loan = Loan::findOrFail($loanId);

            if ($loan->return_date) {
                throw new \Exception('Already returned');
            }

            $loan->return_date = Carbon::now()->toDateString();
            $loan->status = 'returned';
            $loan->save();

            $book = $loan->book;
            $book->available_copies = ($book->available_copies ?? 0) + 1;
            $book->save();

            return $loan->load(['user', 'book']);
        });
    }

    /**
     * Aplicar penalización a un préstamo vencido
     *
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function penalize(int $id): array
    {
        $loan = $this->loans->find($id);

        if ($loan->return_date) {
            throw new \Exception("Este préstamo ya fue devuelto.");
        }

        $already = Penalty::where('loan_id', $loan->id)
            ->whereNotNull('sent_at')
            ->exists();

        if ($already) {
            throw new \Exception("Ya se envió una penalización para este préstamo.");
        }

        $penalty = Penalty::create([
            'loan_id' => $loan->id,
            'user_id' => $loan->user_id,
            'amount' => 5000,
            'reason' => 'Préstamo vencido',
            'sent_at' => Carbon::now()
        ]);

        Mail::to($loan->user->email)
            ->send(new LoanPenaltyMail($loan->user, $loan->book, $loan->due_date));

        $loan->return_date = Carbon::now();
        $loan->save();

        return [
            'loan' => $loan,
            'penalty' => $penalty
        ];
    }

    /**
     * Obtener los libros más prestados
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function topBooks(): Collection
    {
        return $this->loans->topBooks();
    }
}
