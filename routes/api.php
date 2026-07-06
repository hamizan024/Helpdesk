<?php

use App\Http\Controllers\Api\V1\AuthTokenController;
use App\Http\Controllers\Api\V1\TicketApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — v1
|--------------------------------------------------------------------------
| All routes here are prefixed with /api/v1 (set in bootstrap/app.php).
|
| Authentication: POST /api/v1/auth/token → returns a Bearer token.
| Pass it as:  Authorization: Bearer {token}
*/

Route::prefix('v1')->group(function () {

    // --- Auth (public) ---
    Route::post('/auth/token',  [AuthTokenController::class, 'store'])->name('api.auth.token');

    // --- Protected ---
    Route::middleware('auth:sanctum')->group(function () {
        Route::delete('/auth/token', [AuthTokenController::class, 'destroy'])->name('api.auth.token.destroy');

        Route::apiResource('tickets', TicketApiController::class);
    });
});