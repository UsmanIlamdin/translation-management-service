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
Route::middleware('auth.token')->group(function () {
    Route::get('/', [\App\Http\Controllers\TranslationController::class, 'index']);
    Route::post('/translations', [\App\Http\Controllers\TranslationController::class, 'store']);
    Route::put('/translations/{id}', [\App\Http\Controllers\TranslationController::class, 'update']);
});

