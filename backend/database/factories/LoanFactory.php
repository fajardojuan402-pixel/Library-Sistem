<?php

namespace Database\Factories;

use App\Models\Loan;
use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class LoanFactory extends Factory
{
    protected $model = Loan::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'book_id' => Book::factory(),
            'loan_date' => now(),
            'due_date' => Carbon::now()->addDays(14),
            'return_date' => null,
            'status' => 'on_loan',
        ];
    }
}

