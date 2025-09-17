<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\LoanService;
use Illuminate\Http\JsonResponse;

class LoanController extends Controller
{
    protected LoanService $service;

    public function __construct(LoanService $service)
    {
        $this->service = $service;
    }

    /**
     * Listar todos los préstamos
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->service->list()
        ]);
    }

    /**
     * Crear un nuevo préstamo
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id'
        ]);

        try {
            $loan = $this->service->createLoan($data['user_id'], $data['book_id']);

            return response()->json([
                'success' => true,
                'data' => $loan
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Penalizar un préstamo (ej. retrasos)
     */
    public function penalize($id): JsonResponse
    {
        try {
            $result = $this->service->penalize($id);

            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => 'Correo enviado'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Devolver un préstamo
     */
    public function return($loanId): JsonResponse
    {
        try {
            $loan = $this->service->returnLoan($loanId);

            return response()->json([
                'success' => true,
                'data' => $loan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}

