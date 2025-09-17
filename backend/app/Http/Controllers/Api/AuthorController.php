<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Author;
use Illuminate\Http\JsonResponse;

class AuthorController extends Controller
{
    /**
     * Mostrar todos los autores
     */
    public function index(): JsonResponse
    {
        $authors = Author::all();
        return response()->json([
            'success' => true,
            'data' => $authors
        ]);
    }

    /**
     * Crear un nuevo autor
     */
    public function store(Request $request): JsonResponse
    {
        // Validar datos de entrada
        $data = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        // Crear autor
        $author = Author::create($data);

        return response()->json([
            'success' => true,
            'data' => $author
        ], 201);
    }

    /**
     * Mostrar un autor específico
     */
    public function show($id): JsonResponse
    {
        try {
            $author = Author::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $author
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Autor no encontrado'
            ], 404);
        }
    }
}
