<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function() {
    Route::apiResource('/authors', AuthorController::class);
    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/books', BookController::class);
    Route::apiResource('/borrow', BorrowingController::class);
    Route::apiResource('/rating', RatingController::class);
});

