<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PickupLocationController;
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
	Route::get(
		'/perms/admin',
		[PermissionController::class, 'admin']
	);

	Route::get(
		'/perms/estabelecimento',
		[PermissionController::class, 'shop']
	);

	Route::get(
		'/perms/monitor',
		[PermissionController::class, 'consignee']
	);

	Route::get(
		'/perms/level',
		[PermissionController::class, 'accessLevel']
	);

	Route::post(
		'/estabelecimentos/{shop}/remessas/{consignment}/produtos',
		[ConsignmentController::class, 'addProduct'],
	);

	Route::post(
		'/estabelecimentos',
		[ShopController::class, 'store']
	);

	Route::post(
		'/pontos',
		[PickupLocationController::class, 'store']
	);

	Route::get(
		'/pontos',
		[PickupLocationController::class, 'index'],
	);
});

Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);
