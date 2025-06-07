<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ConsigneeController;
use App\Http\Controllers\ConsignmentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);
Route::resource('shops', ShopController::class);
Route::resource('consignments', ConsignmentController::class);
Route::resource('consignees', ConsigneeController::class);

// debug
Route::get('/token', function () {
	return csrf_token();
});

