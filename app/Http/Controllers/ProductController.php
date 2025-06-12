<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;

use App\Models\Product;
use App\Models\Shop;
use App\Models\Sku;
use DB;
use Exception;
use Gate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Str;

class ProductController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		Gate::authorize('viewAny', Product::class);

		$shop = Auth::user()->shop;
		$products = $shop->products;

		return $products->toResourceCollection();
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 */

	public function store(CreateProductRequest $request)
	{
		Gate::authorize('create', Product::class);

		DB::transaction(function () use ($request) {
			$validated = $request->validated();

			$shop = Auth::user()->shop;

			$sku = Sku::firstOrCreate(
				[
					'code' => $validated['sku_code'],
					'shop_id' => $shop->id
				],
				[
					'price' => $validated['price'],
					'quantity' => 0,

					'name' => $validated['name'],
					'slug' => Str::slug($validated['name']),
					'image' => $validated['image'] ?? '',
					'description' => $validated['description'] ?? '',

					'category_id' => $validated['category_id']
				]
			)->lockForUpdate();

			$sku->update([
				'quantity' => $sku->quantity + $validated['quantity']
			]);

			for ($i = 0; $i < $validated['quantity']; $i++) {
				Product::create([
					'sku_id' => $sku->id,
				]);
			}
		});

		return response()->json([
			'success' => true,
			'message' => 'Produto criado com sucesso!'
		], 201);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Shop $shop, Product $product)
	{
		Gate::authorize('view', $product);

		return $product->toResource();
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(string $id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateProductRequest $request, string $id)
	{
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(string $id)
	{
	}
}
