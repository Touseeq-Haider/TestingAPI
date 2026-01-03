<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Protected Routes (Sanctum + Permissions)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // 👀 VIEW (admin, editor, viewer)
    Route::get('books', [BookController::class, 'index'])
        ->middleware('permission:view');

    Route::get('books/{book}', [BookController::class, 'show'])
        ->middleware('permission:view');

    // ✍ CREATE (admin, editor)
    Route::post('books', [BookController::class, 'store'])
        ->middleware('permission:create');

    // ✏ UPDATE (admin, editor)
    Route::put('books/{book}', [BookController::class, 'update'])
        ->middleware('permission:update');

    // ❌ DELETE (admin only)
    Route::delete('books/{book}', [BookController::class, 'destroy'])
        ->middleware('permission:delete');

    // 🔓 Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});
