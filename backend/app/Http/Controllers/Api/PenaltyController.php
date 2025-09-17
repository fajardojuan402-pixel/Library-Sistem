<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penalty;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PenaltyController extends Controller
{
    /**
     * Listar todas las penalizaciones
     */
    public function index(): JsonResponse
    {
        $penalties = Penalty::with(['loan.user', 'loan.book'])->get();

        return response()->json([
            'success' => true,
            'data' => $penalties
        ]);
    }

    /**
     * Mostrar una penalización específica
     */
    public function show($id): JsonResponse
    {
        try {
            $penalty = Penalty::with(['loan.user', 'loan.book'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $penalty
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Penalización no encontrada'
            ], 404);
        }
    }

    /**
     * Eliminar una penalización
     */
    public function destroy($id): JsonResponse
    {
        try {
            $penalty = Penalty::findOrFail($id);
            $penalty->delete();

            return response()->json([
                'success' => true,
                'message' => 'Penalización eliminada correctamente'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo eliminar la penalización'
            ], 404);
        }
    }
}
