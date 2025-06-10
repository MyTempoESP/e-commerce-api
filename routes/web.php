<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ConsigneeController;
use App\Http\Controllers\ConsignmentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;

Route::get('/user', function (Request $request) {
	return Auth::user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
	Route::resource('products', ProductController::class);
	Route::resource('categories', CategoryController::class);
	Route::resource('consignments', ConsignmentController::class);
	Route::resource('consignees', ConsigneeController::class);
	Route::resource('shops', ShopController::class);
});

Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);
