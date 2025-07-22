<?php

use App\Http\Controllers\Chat\pusherController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\V1\Admin\BackendApplicationController;
use App\Http\Controllers\V1\ApplicationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/admin/{view}', BackendApplicationController::class)->where('view', '(.*)');
Route::get('{view}', ApplicationController::class)->where('view', '(.*)');
// Route::get('/login',[LoginController::class,'index']);
// Route::post('/login',[LoginController::class,'login'])->name('login');
// Route::get('/logout',[LoginController::class,'logout'])->name('logout');
// Route::group(['middleware'=>'auth'],function(){
//     Route::get('/test',[TestController::class,'TestMethod'])->name('test');
//     Route::get('/users',[UserController::class,'index'])->name('users');
//     Route::get('/users/{id}',[UserController::class,'edit'])->name('user-edit');
//     Route::put('/users/{id}',[UserController::class,'update'])->name('user-update');
//     Route::delete('/users/{id}',[UserController::class,'destroy'])->name('user-delete');

// });
// Route::get('/chat',[pusherController::class,'index']);
// Route::post('/chat/broadcast',[pusherController::class,'broadcast']);
// Route::post('/chat/receive',[pusherController::class,'receive']);
