<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\CurrentUserController;

Route::post('/login', [SessionController::class, 'store']);

Route::get('/me', [CurrentUserController::class, 'show'])
    ->middleware('auth:sanctum')
    ->name('user.show');

Route::get('/tasks', [TaskController::class, 'index'])
    ->middleware(['auth:sanctum', 'role:admin']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
