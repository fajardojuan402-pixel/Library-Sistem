<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoanTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_a_loan()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['available_copies' => 1]);

        $response = $this->postJson('/api/v1/loans', [
            'user_id' => $user->id,
            'book_id' => $book->id
        ]);

        $response->assertStatus(201)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('loans', ['user_id' => $user->id, 'book_id' => $book->id]);
    }

    /** @test */
    public function cannot_create_loan_when_no_copies()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['available_copies' => 0]);

        $response = $this->postJson('/api/v1/loans', [
            'user_id' => $user->id,
            'book_id' => $book->id
        ]);

        $response->assertStatus(400);
    }
}
