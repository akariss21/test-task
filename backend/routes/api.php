<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/registration', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(JwtMiddleware::class)->group(function () {
    // Профиль и баланс
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/deposit', [BalanceController::class, 'deposit']);
    Route::get('/balance', [BalanceController::class, 'showBalance']);
    
    // Покупки
    Route::post('/products/{product}/purchase', [BalanceController::class, 'purchase']);
    
    // Категории
    Route::apiResource('categories', CategoryController::class)->only(['index', 'store']);
    
    // Заказы
    Route::apiResource('orders', OrderController::class)->except(['update']);
    Route::patch('orders/{order}/complete', [OrderController::class, 'updateStatus']);
    
    // Товары (публичные)
    Route::apiResource('products', ProductController::class)->only(['index', 'show']);
});

// Маршруты только для продавцов
Route::middleware([JwtMiddleware::class, 'can:seller'])->group(function () {
    Route::apiResource('products', ProductController::class)->except(['index', 'show']);
    Route::get('/products/create', [ProductController::class, 'create']);
    Route::get('/products/{product}/edit', [ProductController::class, 'edit']);
});

/*
Route::get('categories', [CategoryController::class, 'index']);
Route::post('categories', [CategoryController::class, 'store']);

Route::get('products', [ProductController::class, 'index']);
Route::post('products', [ProductController::class, 'store']);
Route::get('products/{product}', [ProductController::class, 'list']);
Route::patch('products/{product}', [ProductController::class, 'update']);
Route::delete('products/{product}', [ProductController::class, 'destroy']);

Route::get('orders', [OrderController::class, 'index']);
Route::post('orders', [OrderController::class, 'store']);
Route::get('orders/{order}', [OrderController::class, 'list']);
Route::patch('orders/{id}/complete', [OrderController::class, 'updateStatus']);

Route::post('/registration', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(\App\Http\Middleware\JwtMiddleware::class)->get('/profile', [AuthController::class, 'profile']);
Route::middleware(\App\Http\Middleware\JwtMiddleware::class)->get('/logout', [AuthController::class, 'logout']);

// === Авторизованные пользователи ===
Route::middleware(\App\Http\Middleware\JwtMiddleware::class)->post('/deposit', [BalanceController::class, 'deposit']);
Route::middleware(\App\Http\Middleware\JwtMiddleware::class)->post('/products/{product}/purchase', [BalanceController::class, 'purchase']);

// === Только для продавцов (с авторизацией и проверкой роли) ===
Route::middleware([\App\Http\Middleware\JwtMiddleware::class, 'can:seller'])->post('/products', [ProductController::class, 'store']);
Route::middleware([\App\Http\Middleware\JwtMiddleware::class, 'can:seller'])->put('/products/{product}', [ProductController::class, 'update']);
Route::middleware([\App\Http\Middleware\JwtMiddleware::class, 'can:seller'])->patch('/products/{product}', [ProductController::class, 'update']);
Route::middleware([\App\Http\Middleware\JwtMiddleware::class, 'can:seller'])->delete('/products/{product}', [ProductController::class, 'destroy']);
Route::middleware([\App\Http\Middleware\JwtMiddleware::class, 'can:seller'])->get('/products/{product}/edit', [ProductController::class, 'edit']);
Route::middleware([\App\Http\Middleware\JwtMiddleware::class, 'can:seller'])->get('/products/create', [ProductController::class, 'create']);
*/