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
Route::post('/register', [UserController::class, 'createUser']);    //user registration
Route::post('/login', [UserController::class, 'login']);

// Protected Routes (Sanctum Authentication)
Route::middleware('auth:sanctum')->group(function () {

    // Common (Any Logged-in User)
    Route::post('/logout', [UserController::class, 'logout']);

    // USER Routes (role = user)
    Route::middleware('role:user')->group(function () {

        // user profile        
        Route::put('/users/update/{id}', [UserController::class, 'updateuser']);  //update user id
    

        Route::prefix('meals')->group(function(){
            Route::get('/fetch_all', [MealController::class, 'getAllmeals']);    //fetch all meals
            Route::post('/create', [MealController::class, 'mealDetails']);       //meal details
            Route::get('/fetch/{id}', [MealController::class, 'getmeal_ById']);    //fetch meal by id
            Route::put('/update/{id}', [MealController::class, 'updateMeal']);     //update meal
            Route::delete('/delete/{id}', [MealController::class, 'deleteMeal']);     //delete meal
        });


       Route::prefix('dustbin')->group(function(){
            Route::get('/fetch_all', [DustbinController::class, 'getAllDustbin']);     //fetch all dustbin details
            Route::post('/create', [DustbinController::class, 'setDustbinDetails']);    //store dustbin
            Route::get('/fetch/{id}', [DustbinController::class, 'getDustbin_ById']);   //fetch dustbin by id
            Route::put('/update/{id}', [DustbinController::class, 'updateDustbin']);     //update dustbin
            Route::delete('/delete/{id}', [DustbinController::class, 'deleteDustbin']);  //delete dustbin
         });
    });

    // ADMIN Routes (role = admin)
    Route::middleware('role:admin')->group(function () {

        // users
        Route::get('/users', [UserController::class, 'index']);     // fetch all user
        Route::get('/users/{id}', [UserController::class, 'show']); // fetch user by id        
        Route::delete('/users/{id}', [UserController::class, 'destroy']); // delete user

        // admin dashboard

        // Deposite Routes
        Route::prefix('deposits')->group(function(){
            Route::get('/fetch_all', [DepositController::class, 'getAllDeposite']);          // fetch deposite by id  
            Route::post('/create', [DepositController::class, 'addDeposite']);            // create deposite 
            Route::get('/fetch/{id}', [DepositController::class, 'getDeposite_byId']);   // fetch deposite by id  
            Route::put('/update/{id}', [DepositController::class, 'updateDeposite']);     // Update Deposite
            Route::delete('/delete/{id}', [DepositController::class, 'deleteDeposite']);  // delete deposite
        });
        
          

        //  Marketing Routes
        Route::prefix('marketing')->group(function(){
            Route::get('/fetch_all', [MarketingController::class, 'getAllMarketing']);    //fetch all marketing
            Route::post('/create', [MarketingController::class, 'RegisterMarketing']);  //register market details
            Route::get('/fetch/{id}', [MarketingController::class, 'getMarketing_byId']);  //fetch marketing by id
            Route::put('/update/{id}', [MarketingController::class, 'updateMarketing']);   //update marketing
            Route::delete('/delete/{id}', [MarketingController::class, 'deleteMarket']);   //delete market details
        });
 


        Route::prefix('user_meal')->group(function(){
            Route::get('/fetch_all', [UserMealController::class, 'getusersAllMeal']);      //fetch user all meal
            Route::post('/create', [UserMealController::class, 'usermealDetails']);       //user meal details
            Route::get('/fetch/{id}', [UserMealController::class, 'getuserMeal_byId']);     // fetch user meal by id
            Route::put('/update/{id}', [UserMealController::class, 'updateUserMeal']);    //update user meal
            Route::delete('/delete/{id}', [UserMealController::class, 'deleteUserMeal']);   //delete user meal
             });
    });

});
