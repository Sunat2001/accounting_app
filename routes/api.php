<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', RegisterController::class);
    Route::post('login', LoginController::class);
    Route::post('logout', LoginController::class)->middleware('auth:sanctum');
});

Route::prefix('transaction')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [TransactionController::class, 'index']);
    Route::post('/', [TransactionController::class, 'store']);
    Route::get('/{transaction}', [TransactionController::class, 'show']);
    Route::delete('/{transaction}', [TransactionController::class, 'destroy']);
});
