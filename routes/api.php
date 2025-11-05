<?php

use App\Http\Controllers\MediaController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\CurrentUserController;

Route::post('/login', [SessionController::class, 'store']);

Route::get('/me', [CurrentUserController::class, 'show'])
    ->middleware('auth:sanctum')
    ->name('user.show');

Route::get('/tasks', [TaskController::class, 'index'])
    ->middleware(['auth:sanctum']);

Route::get('/tasks/{task}', [TaskController::class, 'show'])
    ->middleware(['auth:sanctum']);

Route::get('/users', [UserController::class, 'index'])
    ->middleware(['auth:sanctum']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/tasks/{task}/media', [MediaController::class, 'store']);
    Route::get('/media/{media}', [MediaController::class, 'show']);
    Route::delete('/media/{media}', [MediaController::class, 'destroy']);
});
