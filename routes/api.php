<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/health', [\App\Http\Controllers\Api\IndexController::class, 'health']);

Route::middleware(['api', 'auth.token'])->group(function () {
    Route::get('/translations', [\App\Http\Controllers\Api\TranslationController::class, 'index']);
    Route::get('/translations/{locale}', [\App\Http\Controllers\Api\TranslationController::class, 'index']);
    Route::get('/translations/{locale}/tags/{tag}', [\App\Http\Controllers\Api\TranslationController::class, 'index']);
    Route::post('/translations', [\App\Http\Controllers\Api\TranslationController::class, 'store']);
    Route::put('/translations/{id}', [\App\Http\Controllers\Api\TranslationController::class, 'update']);
    Route::delete('/translations/{id}', [\App\Http\Controllers\Api\TranslationController::class, 'destroy']);
});

