<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware(['auth:sanctum', 'role:admin'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/dashboard', fn() => response()->json(['message' => 'You are an admin']));
        Route::apiResource('/categories', CategoryController::class);
    });

Route::middleware(['auth:sanctum', 'role:seller'])
    ->prefix('seller')
    ->group(function () {
        Route::get('/dashboard', fn() => response()->json(['message' => 'You are a seller']));
    });

Route::middleware(['auth:sanctum', 'role:customer'])
    ->prefix('customer')
    ->group(function () {
        Route::get('/dashboard', fn() => response()->json(['message' => 'You are a customer']));
    });
