<?php

use App\Http\Controllers\Auth\AuthClientController;
use App\Http\Controllers\Auth\AuthUserController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;

//Route::middleware('auth:sanctum')->group(function () {
//    // Rotte API per Persona
//    Route::apiResource('personas', PersonaController::class);
//});

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

Route::get('export/personas', [ExportController::class, 'exportPersonas']);
//Route::middleware('auth:user')->get('export/personas', [ExportController::class, 'exportPersonas']);

//Route::get('export/personas', [ExportController::class, 'exportPersonas']);

//Route::middleware('auth:client')->group(function () {
//    Route::post('auth/logout', [AuthUserController::class, 'logout']);
//    Route::get('auth/user', [AuthUserController::class, 'getUser']);
//});

// Rotte protette per gli utenti (Sanctum)
//Route::middleware(['auth:sanctum'])->group(function () {
//    Route::apiResource('/personas', PersonaController::class);
//});
//
//// Rotte protette per i client (JWT)
//Route::middleware(['check.user.type:client'])->group(function () {
//    Route::apiResource('/personas', PersonaController::class);
//});


//Route::middleware('auth:sanctum')->group(function () {
//    Route::apiResource('/personas', PersonaController::class);
//});

//Route::middleware('auth')->group(function () {
//    Route::apiResource('personas', PersonaController::class);
//});

Route::apiResource('personas', PersonaController::class)->middleware('auth:user');

//Route::middleware('auth:client')->group(function () {
//    Route::apiResource('/personas', PersonaController::class);
//});
