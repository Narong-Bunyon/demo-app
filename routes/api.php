<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookApiController;

/*
|--------------------------------------------------------------------------
| Mobile API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // Book Endpoints for Mobile App
    Route::get('/books', [BookApiController::class, 'index']);
    Route::post('/books', [BookApiController::class, 'store']);
    Route::get('/books/{id}', [BookApiController::class, 'show']);
    Route::put('/books/{id}', [BookApiController::class, 'update']);
    Route::patch('/books/{id}', [BookApiController::class, 'update']);
    Route::delete('/books/{id}', [BookApiController::class, 'destroy']);

    // Auxiliary endpoints
    Route::get('/genres', [BookApiController::class, 'genres']);
    Route::get('/stats', [BookApiController::class, 'stats']);
});
