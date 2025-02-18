<?php

use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\DailyScheduleController;
use App\Http\Controllers\MoodController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PublicMessageController;
use App\Http\Controllers\RedditQuoteController;
use App\Http\Controllers\ShippingAddressController;
use App\Http\Controllers\TodoListController;
use App\Http\Controllers\V1\Admin\AdminDashboardController;
use App\Http\Controllers\V1\Admin\AdminOrderController;
use App\Http\Controllers\V1\Admin\GetAdminDataController;
use App\Http\Controllers\V1\Admin\ToggleLockUserFromAdminController;
use App\Http\Controllers\V1\AdminController;
use App\Http\Controllers\V1\AdminLoginController;
use App\Http\Controllers\V1\CartController;
use App\Http\Controllers\V1\CategoryController;
use App\Http\Controllers\V1\KhaltiPaymentController;
use App\Http\Controllers\V1\OrderController;
use App\Http\Controllers\V1\ProductController;
use App\Http\Controllers\V1\RatingController;
use App\Http\Controllers\V1\UserController;
use App\Http\Controllers\WebScraperController;
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
// Route::apiResource('/blog-categories', BlogCategory::class);
Route::get('/blog-categories', [BlogCategoryController::class, 'index']);
Route::get('/blog-categories/{id}', [BlogCategoryController::class, 'show']);

Route::get('/scrape', [WebScraperController::class, 'scrape']);
Route::get('/blogs', [BlogController::class, 'index']);
Route::post('/blogs', [BlogController::class, 'store']);
Route::delete('/blogs/{id}-{slug}', [BlogController::class, 'destroy']);
Route::get('/blogs/{id}-{slug}', [BlogController::class, 'show']);

Route::get('/payment/charge', [PaymentController::class, 'charge']);
Route::get('/reddit/test', [RedditQuoteController::class, 'test']);

Route::group(['prefix' => 'V1'], function () {
    Route::post('/users/register', [UserController::class, 'register'])->name('api-user-register');
    Route::post('/users/login', [UserController::class, 'login'])->name('login');

    Route::get('/users', [UserController::class, 'index'])->name('api-user-index');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('api-user-show');
    Route::group(['middleware' => ['auth:user', 'checkUserLocked']], function () {
        Route::get('/users-data', [UserController::class, 'getUserData'])->name('api-user-data');
        Route::put('/users-data/{id}', [UserController::class, 'updateFromUser'])->name('api-user-data-update');
        Route::post('/users/logout', [UserController::class, 'logout'])->name('api-user-logout');
        Route::resource('/carts', CartController::class);
        Route::get('/carts-count', [CartController::class, 'getCartCount'])->name('api-cart-count');
        Route::resource('/orders', OrderController::class);
        Route::post('/shipping-address', [ShippingAddressController::class, 'store'])->name('api-shipping-address-store');
        Route::get('/shipping-address/self', [ShippingAddressController::class, 'show'])->name('api-shipping-address-show');
        Route::post('/ratings-comment/{product_id}', [RatingController::class, 'reviewStore']);
        Route::post('/rating/{product_id}', [RatingController::class, 'ratingStore']);
        Route::post('/pay', [KhaltiPaymentController::class, 'pay']);
        Route::post('/lookup', [KhaltiPaymentController::class, 'lookup']);

        Route::get('/public-chat', [PublicMessageController::class, 'index']);
        Route::post('/public-chat', [PublicMessageController::class, 'store']);
    });
    Route::get('/ratings/{product_id}', [RatingController::class, 'index']);
    Route::get('/products', [ProductController::class, 'index'])->name('api-products.index');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('api-products.show');
    Route::get('/related-products', [ProductController::class, 'related'])->name('api-products.related');
    Route::post('/admins/login', [AdminLoginController::class, 'login'])->name('api-admin-login');

    Route::group(['middleware' => ['auth:admin']], function () {
        Route::get('/admin-data', GetAdminDataController::class);
        Route::get('/admin-dashboard', AdminDashboardController::class);
        Route::patch('/users/toggle-lock', ToggleLockUserFromAdminController::class);
        Route::put('/users/{id}', [UserController::class, 'update'])->name('api-user-update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('api-user-delete');
        Route::apiResource('/categories', CategoryController::class);
        Route::resource('/products', ProductController::class)->except('index', 'show');
        Route::patch('/admin-status-orders/{id}', [AdminOrderController::class, 'statusUpdate'])->name('api-admin-status-orders');
        Route::resource('/admin-orders', AdminOrderController::class)->name('show', 'api-admin-orders.show');
        Route::post('/admins/logout', [AdminLoginController::class, 'logout'])->name('api-admin-logout');
        Route::resource('/admins', AdminController::class);
        Route::get('/admins-daily-schedule/{date?}', [DailyScheduleController::class, 'index']);
        Route::get('/admins-daily-schedule-Monthly/{date?}', [DailyScheduleController::class, 'getTasksMonths']);
        Route::get('/admins-daily-schedule/{id}', [DailyScheduleController::class, 'show']);
        Route::post('/admins-daily-schedule', [DailyScheduleController::class, 'store']);
        Route::patch('/admins-daily-schedule', [DailyScheduleController::class, 'update']);
        Route::patch('/admins-daily-schedule-update-status/{id}', [DailyScheduleController::class, 'updateStatus']);
        Route::post('/admins-moods', [MoodController::class, 'store']);
        Route::get('/admins-moods-latest', [MoodController::class, 'latest']);
        Route::get('/admins-todo-list', [TodoListController::class, 'index']);
        Route::post('/admins-todo-list', [TodoListController::class, 'store']);
        Route::patch('/admins-todo-list/{id}', [TodoListController::class, 'update']);
        Route::patch('/admins-todo-list-update-status/{id}', [TodoListController::class, 'updateCompleted']);
    });
});
