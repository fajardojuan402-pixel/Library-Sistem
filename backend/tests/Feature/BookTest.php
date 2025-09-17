<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Author;
use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_a_book()
    {
        $author = Author::factory()->create();
        $genre = Genre::factory()->create();

        $response = $this->postJson('/api/v1/books', [
            'title' => 'Libro de prueba',
            'author_id' => $author->id,
            'genre_id' => $genre->id,
            'isbn' => '1234567890123',
            'total_copies' => 3
        ]);

        $response->assertStatus(201)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('books', ['title' => 'Libro de prueba']);
    }

    /** @test */
    public function can_update_a_book()
    {
        $book = Book::factory()->create();

        $response = $this->putJson("/api/v1/books/{$book->id}", [
            'title' => 'Libro actualizado'
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('books', ['title' => 'Libro actualizado']);
    }

    /** @test */
    public function can_delete_a_book()
    {
        $book = Book::factory()->create();

        $response = $this->deleteJson("/api/v1/books/{$book->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }
}
