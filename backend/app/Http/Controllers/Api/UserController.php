<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Listar todos los usuarios
     */
    public function index(): JsonResponse
    {
        $users = User::all();
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Crear un nuevo usuario
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'phone' => 'nullable|string|max:20'
        ]);

        $user = User::create($data);

        return response()->json([
            'success' => true,
            'data' => $user
        ], 201);
    }

    /**
     * Mostrar un usuario específico
     */
    public function show($id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }
    }

    /**
     * Actualizar un usuario
     */
    public function update(Request $request, $id): JsonResponse
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $id . '|max:255',
            'phone' => 'nullable|string|max:20'
        ]);

        try {
            $user = User::findOrFail($id);
            $user->update($data);

            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo actualizar el usuario'
            ], 404);
        }
    }

    /**
     * Eliminar un usuario
     */
    public function destroy($id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado correctamente'
            ], 204);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo eliminar el usuario'
            ], 404);
        }
    }
}
