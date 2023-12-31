<?php

use App\Http\Controllers\FoodController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('users/authenticate', [UserController::class, 'login']);

Route::middleware('checkAuth')->group(function () {
    Route::get('foods', [FoodController::class, 'index']);
    Route::post('foods', [FoodController::class, 'store']);
    Route::get('foods/{id}', [FoodController::class, 'show']);
    Route::put('foods/{id}', [FoodController::class, 'update']);
    Route::delete('foods/{id}', [FoodController::class, 'destroy']);
});

Route::middleware('checkServer')->get('/', function () {
    return response()->json(['message' => 'Server is running.'], 200);
});
