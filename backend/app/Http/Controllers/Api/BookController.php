<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BookService;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{
    protected BookService $service;

    public function __construct(BookService $service)
    {
        $this->service = $service;
    }

    /**
     * Mostrar todos los libros
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->service->list()
        ]);
    }

    /**
     * Crear un nuevo libro
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'genre_id' => 'required|exists:genres,id',
            'isbn' => 'required|unique:books,isbn',
            'total_copies' => 'required|integer|min:1'
        ]);

        $book = $this->service->create($data);

        return response()->json([
            'success' => true,
            'data' => $book
        ], 201);
    }

    /**
     * Mostrar un libro específico
     */
    public function show($id): JsonResponse
    {
        try {
            $book = $this->service->get($id);

            return response()->json([
                'success' => true,
                'data' => $book
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Libro no encontrado'
            ], 404);
        }
    }

    /**
     * Actualizar un libro
     */
    public function update(Request $request, $id): JsonResponse
    {
        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'author_id' => 'sometimes|required|exists:authors,id',
            'genre_id' => 'sometimes|required|exists:genres,id',
            'isbn' => 'sometimes|required|unique:books,isbn,' . $id,
            'total_copies' => 'sometimes|required|integer|min:1'
        ]);

        try {
            $book = $this->service->update($id, $data);

            return response()->json([
                'success' => true,
                'data' => $book
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo actualizar el libro'
            ], 400);
        }
    }

    /**
     * Eliminar un libro
     */
    public function destroy($id): JsonResponse
    {
        try {
            $this->service->delete($id);

            return response()->json([
                'success' => true
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo eliminar el libro'
            ], 400);
        }
    }
}

