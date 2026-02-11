<?php

use App\Http\Controllers\Api\ExpensesController;
use App\Http\Controllers\Api\UserPocketController;
use App\Http\Controllers\Api\IncomesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth:api')->group(function () {
    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::prefix('pockets')->group(function () {
        Route::post('/', [UserPocketController::class, 'store']);
        Route::get('/', [UserPocketController::class, 'index']);

        Route::get('total-balance', [UserPocketController::class, 'totalBalance']);

        Route::post('/{id}/create-report', [UserPocketController::class, 'createReport']);
    });

    Route::prefix('incomes')->group(function () {
        Route::post('/', [IncomesController::class, 'store']);
    });

    Route::prefix('expenses')->group(function () {
        Route::post('/', [ExpensesController::class, 'store']);
    });
});
