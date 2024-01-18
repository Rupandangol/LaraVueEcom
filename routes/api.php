<?php

use App\Http\Controllers\V1\AdminController;
use App\Http\Controllers\V1\CartController;
use App\Http\Controllers\V1\CategoryController;
use App\Http\Controllers\V1\OrderController;
use App\Http\Controllers\V1\ProductController;
use App\Http\Controllers\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'V1'], function () {
    Route::post('/users/register', [UserController::class, 'register'])->name('api-user-register');
    Route::post('/users/login', [UserController::class, 'login'])->name('api-user-login');


    Route::get('/users', [UserController::class, 'index'])->name('api-user-index');
    Route::get('/users', [UserController::class, 'index'])->name('api-user-index');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('api-user-show');
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/users/logout', [UserController::class, 'logout'])->name('api-user-logout');
        Route::resource('/categories', CategoryController::class);
        Route::resource('/products', ProductController::class);
        Route::resource('/carts', CartController::class);
        Route::resource('/orders', OrderController::class);
    });

    Route::resource('/admins', AdminController::class);
});
