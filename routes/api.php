<?php

use App\Http\Controllers\Auth\AuthClientController;
use App\Http\Controllers\Auth\AuthUserController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\PersonaController;
use Illuminate\Support\Facades\Route;

// Rotte per l'autenticazione User
Route::post('auth/user/login', [AuthUserController::class, 'login']);
Route::middleware('auth:user')->group(function () {
    Route::post('auth/user/logout', [AuthUserController::class, 'logout']);
    Route::get('auth/user/profile', [AuthUserController::class, 'getProfile']);
});

// Rotte per l'autenticazione Client
Route::post('auth/client/login', [AuthClientController::class, 'login']);
Route::middleware('auth:client')->group(function () {
    Route::post('auth/client/logout', [AuthClientController::class, 'logout']);
    Route::get('auth/client/profile', [AuthClientController::class, 'getProfile']);
});

// Rotte per l'export csv
Route::middleware(['multiAuth:user,client'])->group(function () {
    Route::get('export/personas', [ExportController::class, 'exportPersonas']);
});

// Rotte per persona
Route::middleware(['multiAuth:user,client'])->group(function () {
    Route::get('/personas', [PersonaController::class, 'index']);

    Route::middleware('checkRole:ROLE_ADMIN')->group(function () {
        Route::post('/personas', [PersonaController::class, 'store']);
        Route::get('/personas/{id}', [PersonaController::class, 'show']);
        Route::put('/personas/{id}', [PersonaController::class, 'update']);
        Route::delete('/personas/{id}', [PersonaController::class, 'destroy']);
    });
});



