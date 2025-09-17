<?php

namespace App\Services;

use App\Repositories\BookRepository;
use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;

class BookService
{
    protected BookRepository $books;

    public function __construct(BookRepository $books)
    {
        $this->books = $books;
    }

    /**
     * Listar todos los libros
     *
     * @return Collection
     */
    public function list(): Collection
    {
        return $this->books->all();
    }

    /**
     * Obtener un libro por ID
     *
     * @param int $id
     * @return Book
     */
    public function get(int $id): Book
    {
        return $this->books->find($id);
    }

    /**
     * Crear un nuevo libro
     *
     * @param array $data
     * @return Book
     */
    public function create(array $data): Book
    {
        $data['available_copies'] = $data['total_copies'] ?? 1;
        return $this->books->create($data);
    }

    /**
     * Actualizar un libro existente
     *
     * @param int $id
     * @param array $data
     * @return Book
     */
    public function update(int $id, array $data): Book
    {
        $book = $this->books->find($id);

        if (isset($data['total_copies'])) {
            $prestados = $book->total_copies - $book->available_copies;
            $data['available_copies'] = max($data['total_copies'] - $prestados, 0);
        }

        return $this->books->update($book, $data);
    }

    /**
     * Eliminar un libro
     *
     * @param int $id
     * @return bool|null
     */
    public function delete(int $id): ?bool
    {
        $book = $this->books->find($id);
        return $this->books->delete($book);
    }
}
