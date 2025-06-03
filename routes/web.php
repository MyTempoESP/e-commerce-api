<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::resource('products', ProductController::class);

// debug
Route::get('/token', function () {
	return csrf_token();
});

