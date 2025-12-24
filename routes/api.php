<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DepositController;
use App\Http\Controllers\Api\MealController;
use App\Http\Controllers\Api\MarketingController;
use App\Http\Controllers\Api\DustbinController;
use App\Http\Controllers\Api\UserMealController;

// Public Routes (No Login Required)
Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);

// Protected Routes (Sanctum Authentication)
Route::middleware('auth:sanctum')->group(function () {

    // Common (Any Logged-in User)
    Route::post('/logout', [UserController::class, 'logout']);

    // USER Routes (role = user)
    Route::middleware('role:user')->group(function () {

        // user profile
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);

        Route::get('/meals', [MealController::class, 'index']);
        Route::post('/meals', [MealController::class, 'store']);
        Route::get('/meals/{id}', [MealController::class, 'show']);
        Route::put('/meals/{id}', [MealController::class, 'update']);
        Route::delete('/meals/{id}', [MealController::class, 'destroy']);

        Route::get('/dustbin', [DustbinController::class, 'index']);
        Route::post('/dustbin', [DustbinController::class, 'store']);
        Route::get('/dustbin/{id}', [DustbinController::class, 'show']);
        Route::put('/dustbin/{id}', [DustbinController::class, 'update']);
        Route::delete('/dustbin/{id}', [DustbinController::class, 'destroy']);
    });

    // ADMIN Routes (role = admin)
    Route::middleware('role:admin')->group(function () {

        // admin dashboard
        Route::get('/deposits', [DepositController::class, 'index']);
        Route::post('/deposits', [DepositController::class, 'store']);
        Route::get('/deposits/{id}', [DepositController::class, 'show']);
        Route::put('/deposits/{id}', [DepositController::class, 'update']);
        Route::delete('/deposits/{id}', [DepositController::class, 'destroy']);

        Route::get('/marketing', [MarketingController::class, 'index']);
        Route::post('/marketing', [MarketingController::class, 'store']);
        Route::get('/marketing/{id}', [MarketingController::class, 'show']);
        Route::put('/marketing/{id}', [MarketingController::class, 'update']);
        Route::delete('/marketing/{id}', [MarketingController::class, 'destroy']);

        Route::get('/user-meals', [UserMealController::class, 'index']);
        Route::post('/user-meals', [UserMealController::class, 'store']);
        Route::get('/user-meals/{id}', [UserMealController::class, 'show']);
        Route::put('/user-meals/{id}', [UserMealController::class, 'update']);
        Route::delete('/user-meals/{id}', [UserMealController::class, 'destroy']);
    });

});
