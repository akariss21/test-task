<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;

use Illuminate\Support\Facades\Route;

Route::post('/registration', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/user/become-seller', [UserController::class, 'becomeSeller']);
    Route::get('/user/role', [UserController::class, 'role']);
    Route::get('/balance', [BalanceController::class, 'show']); // показать текущий баланс
    Route::post('/transactions/deposit', [TransactionController::class, 'deposit']);       // пополнение баланса
    Route::post('/transactions/withdraw', [TransactionController::class, 'withdraw']);     // вывод средств
    Route::post('/transactions/purchase', [TransactionController::class, 'purchase']);     // покупка (списание с баланса)
    Route::get('products', [ProductController::class, 'index']);
    Route::post('products', [ProductController::class, 'store']);
    Route::get('products/{product}', [ProductController::class, 'list']);
    Route::patch('products/{product}', [ProductController::class, 'update']);
    Route::delete('products/{product}', [ProductController::class, 'destroy']);
    
    Route::get('categories', [CategoryController::class, 'index']);
    Route::post('categories', [CategoryController::class, 'store']);

    Route::get('orders', [OrderController::class, 'index']);
    Route::post('orders', [OrderController::class, 'store']);
    Route::get('orders/{order}', [OrderController::class, 'list']);
    Route::patch('orders/{id}/complete', [OrderController::class, 'updateStatus']);

});

// Route::middleware('auth:api')->get('/me', function () {       //для проверки
//     return response()->json(auth()->user());
// });

