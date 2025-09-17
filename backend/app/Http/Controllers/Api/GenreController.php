<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Genre;
use Illuminate\Http\JsonResponse;

class GenreController extends Controller
{
    /**
     * Mostrar todos los géneros
     */
    public function index(): JsonResponse
    {
        $genres = Genre::all();
        return response()->json([
            'success' => true,
            'data' => $genres
        ]);
    }

    /**
     * Crear un nuevo género
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $genre = Genre::create($data);

        return response()->json([
            'success' => true,
            'data' => $genre
        ], 201);
    }

    /**
     * Mostrar un género específico
     */
    public function show($id): JsonResponse
    {
        try {
            $genre = Genre::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $genre
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Género no encontrado'
            ], 404);
        }
    }

    /**
     * Actualizar un género
     */
    public function update(Request $request, $id): JsonResponse
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255'
        ]);

        try {
            $genre = Genre::findOrFail($id);
            $genre->update($data);

            return response()->json([
                'success' => true,
                'data' => $genre
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo actualizar el género'
            ], 404);
        }
    }

    /**
     * Eliminar un género
     */
    public function destroy($id): JsonResponse
    {
        try {
            $genre = Genre::findOrFail($id);
            $genre->delete();

            return response()->json([
                'success' => true
            ], 204);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo eliminar el género'
            ], 404);
        }
    }
}
