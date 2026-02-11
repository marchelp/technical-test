<?php

use App\Http\Controllers\Api\UserPocketController;
use App\Http\Controllers\Api\IncomesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::get('profile', [AuthController::class, 'profile']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

Route::prefix('pockets')->middleware('auth:api')->group(function () {
    Route::post('/', [UserPocketController::class, 'store']);
    Route::get('/', [UserPocketController::class, 'index']);
});


Route::prefix('incomes')->middleware('auth:api')->group(function () {
    Route::post('/', [IncomesController::class, 'store']);
});
