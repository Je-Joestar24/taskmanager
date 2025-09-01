<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/test', function (Request $request) {
    return  response()->json(app()->version());
});

Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegisterUserController::class, 'store']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);
    
    // Task management routes
    Route::prefix('tasks')->group(function () {
        Route::get('/', [App\Http\Controllers\User\TasksController::class, 'index']);
        Route::post('/', [App\Http\Controllers\User\TasksController::class, 'store']);
        Route::get('/statistics', [App\Http\Controllers\User\TasksController::class, 'statistics']);
        Route::get('/{task}', [App\Http\Controllers\User\TasksController::class, 'show']);
        Route::put('/{task}', [App\Http\Controllers\User\TasksController::class, 'update']);
        Route::delete('/{task}', [App\Http\Controllers\User\TasksController::class, 'destroy']);
        Route::patch('/{task}/toggle-status', [App\Http\Controllers\User\TasksController::class, 'toggleStatus']);
        Route::post('/reorder', [App\Http\Controllers\User\TasksController::class, 'reorder']);
    });

    // Admin routes (protected by CheckAdmin middleware)
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'dashboard']);
        Route::get('/users', [App\Http\Controllers\Admin\AdminController::class, 'users']);
        Route::get('/tasks', [App\Http\Controllers\Admin\AdminController::class, 'allTasks']);
        Route::get('/users/{user}/stats', [App\Http\Controllers\Admin\AdminController::class, 'userStats']);
    });
});