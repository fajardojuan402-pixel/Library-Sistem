<?php

namespace App\Repositories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;

class BookRepository
{
    /**
     * Obtener todos los libros con sus relaciones
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return Book::with(['author', 'genre'])->get();
    }

    /**
     * Buscar un libro por ID con sus relaciones
     *
     * @param int $id
     * @return Book
     */
    public function find(int $id): Book
    {
        return Book::with(['author', 'genre'])->findOrFail($id);
    }

    /**
     * Crear un nuevo libro
     *
     * @param array $data
     * @return Book
     */
    public function create(array $data): Book
    {
        return Book::create($data);
    }

    /**
     * Actualizar un libro existente
     *
     * @param Book $book
     * @param array $data
     * @return Book
     */
    public function update(Book $book, array $data): Book
    {
        $book->update($data);
        return $book;
    }

    /**
     * Eliminar un libro
     *
     * @param Book $book
     * @return bool|null
     */
    public function delete(Book $book): ?bool
    {
        return $book->delete();
    }
}
