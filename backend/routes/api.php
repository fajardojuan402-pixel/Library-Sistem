<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\LoanController;
use App\Http\Controllers\Api\StatsController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\GenreController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PenaltyController;

Route::prefix('v1')->group(function() {
    Route::apiResource('books', BookController::class);
    Route::apiResource('authors', AuthorController::class)->only(['index','store','show']);
    Route::apiResource('genres', GenreController::class)->only(['index','store','show']);
    Route::apiResource('users', UserController::class)->only(['index','store','show']);
    Route::post('loans', [LoanController::class,'store']);
    Route::put('loans/{loan}/return', [LoanController::class,'return']);
    Route::get('loans', [LoanController::class,'index']);
    Route::get('stats/top-books', [StatsController::class,'topBooks']);
    Route::get('stats/availability', [StatsController::class,'availability']);
    Route::get('stats/loans-per-month', [StatsController::class,'loansPerMonth']);
    Route::get('/stats/penalties', [StatsController::class, 'penalties']);
    Route::post('loans/{id}/penalize', [LoanController::class, 'penalize']);
    Route::get('/penalties', [PenaltyController::class, 'index']);
    Route::get('/penalties/{id}', [PenaltyController::class, 'show']);
    Route::delete('/penalties/{id}', [PenaltyController::class, 'destroy']);

});
