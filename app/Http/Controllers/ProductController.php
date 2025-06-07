<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;

use App\Models\Product;
use App\Models\Shop;
use App\Models\Sku;
use DB;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Str;

class ProductController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$products = Product::all();

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
		try {
			DB::transaction(function () use ($request) {
				$validated = $request->validated();

				$sku = Sku::firstOrCreate(
					[
						'code' => $validated['sku_code'],
						'shop_id' => $validated['shop_id']
					],
					[
						'price' => $validated['price'],
						'quantity' => 0,

						'name' => $validated['name'],
						'slug' => Str::slug($validated['name']),
						'description' => $validated['description'] ?? '',
						'image' => $validated['image'] ?? '',

						'category_id' => $validated['category_id']
					]
				);

				$sku->lockForUpdate();

				$sku->quantity = $sku->quantity + $validated['quantity'];

				$sku->save();

				for ($i = 0; $i < $validated['quantity']; $i++) {
					Product::create([
						'sku_id' => $sku->id,
						// qr_code
					]);
				}
			});

			return response()->json([
				'success' => true,
				'message' => 'Produto criado com sucesso!'
			], 201);
		} catch (Exception $e) {
			return response()->json([
				'success' => false,
				'message' => $e->getMessage()
			], 500);
		}
	}

	/**
	 * Display the specified resource.
	 */
	public function show(string $id)
	{
		$product = Product::findOrFail($id);

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
		$data = $request->all();

		try {
			$product = Product::findOrFail($id);
		} catch (ModelNotFoundException) {
			return response()->json([
				'success' => false,
				'message' => 'Produto não encontrado!'
			]);
		}

		$product->update($data);

		return response()->json([
			'success' => true,
			'message' => 'Produto atualizado com sucesso!'
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(string $id)
	{
		try {
			$product = Product::findOrFail($id);
		} catch (ModelNotFoundException) {
			return response()->json([
				'success' => false,
				'message' => 'Produto não encontrado!'
			]);
		}

		$product->delete();

		return response()->json([
			'success' => true,
			'message' => 'Produto deletado com sucesso!'
		]);
	}
}
