<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Penalty;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PenaltyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_penalize_a_loan()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['available_copies' => 1]);
        $loan = Loan::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'return_date' => null
        ]);

        $response = $this->postJson("/api/v1/loans/{$loan->id}/penalize");

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('penalties', ['loan_id' => $loan->id, 'user_id' => $user->id]);
    }
}
