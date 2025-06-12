<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;

use App\Models\Consignment;
use App\Models\Customization;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Sku;
use DB;
use Exception;
use Gate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

	public function consignmentIndex(Consignment $consignment)
	{
		Gate::authorize('view', $consignment);

		return $consignment->products->toResourceCollection();
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

		$validated = $request->validated();

		/**
		 * @var Shop
		 */
		$shop = Auth::user()->shop;

		/**
		 * @var \App\Models\Category
		 * TODO: fetch by id or name?
		 */
		$category = $shop->categories
			->findOrFail($validated['categoryId']);

		$code = Sku::generateCode($validated);

		/**
		 * @var Sku
		 */
		$sku = DB::transaction(function () use ($validated, $shop, $code, $category) {
			/**
			 * @var Sku
			 */
			$sku = $shop->skus()->lockForUpdate()
				->where(
					'code',
					$code
				)->firstOrCreate(
					[
						'price' => $validated['price'],
						'quantity' => 0,

						'discount' => $validated['discount'],

						'image' => $validated['imageUrl'],
						'description' => $validated['description'],

						// url Arrays
						'desc_images' => $validated['descriptionImages'],
						'spec_images' => $validated['specificationsImages'],
						'pack_images' => $validated['deliveryImages'],

						'featured' => $validated['featured'],

						'category_id' => $category->id,
						'shop_id' => $shop->id
					]
				);

			$sku->update([
				'quantity' => $sku->quantity + $validated['quantity']
			]);

			return $sku;
		});

		DB::transaction(function () use ($sku, $validated) {
			$sku->customizations()
				->firstOrCreate(
					[
						'name' => 'color'
					],
					[
						'options' => $validated['colors'],
						'enabled' => $validated['allowCustomColorSelection'],
						'sku_id' => $sku->id
					]
				);

			$sku->customizations()
				->firstOrCreate(
					[
						'name' => 'name'
					],
					[
						'enabled' => $validated['allowCustomName'],
						'sku_id' => $sku->id
					]
				);

			$sku->customizations()
				->firstOrCreate(
					[
						'name' => 'modality'
					],
					[
						'enabled' => $validated['allowCustomModality'],
						'sku_id' => $sku->id
					]
				);
		});

		DB::transaction(function () use ($sku, $validated) {
			for ($i = 0; $i < $validated['quantity']; $i++) {
				Product::create([
					'sku_id' => $sku->id
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
