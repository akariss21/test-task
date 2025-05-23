<?php

use App\Http\Controllers\AuthController;
use App\
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

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