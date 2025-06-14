<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;

use App\Models\Consignment;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Sku;
use DB;
use Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		Gate::authorize('viewAny', Product::class);

		$shop = Auth::user()->shop;
		$skus = $shop->products;

		return $skus->toResourceCollection();
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
		$category = $shop->categories()
			->firstOrCreate(
				[
					'name' => $validated['category']
				],
				[
					'slug' => Str::slug($validated['category']),
					'shop_id' => $shop->id
				]
			);

		$code = product::generateCode($validated);

		/**
		 * @var Product
		 */
		$product = DB::transaction(function () use ($validated, $shop, $code, $category) {
			/**
			 * @var Product
			 */
			$product = $shop->products()->lockForUpdate()
				->firstOrCreate(
					[
						'code' => $code
					],
					[
						'uuid' => Str::uuid(),
						'name' => $validated['name'],

						'price' => $validated['price'],
						'quantity' => 0,

						'discount' => $validated['discount'],

						'image' => $validated['image_url'] ?? '',
						'description' => $validated['description'],

						// url Arrays
						'desc_images' => $validated['descriptionImages'] ?? '[]',
						'spec_images' => $validated['specificationsImages'] ?? '[]',
						'pack_images' => $validated['deliveryImages'] ?? '[]',

						'featured' => $validated['featured'],

						'category_id' => $category->id,
						'shop_id' => $shop->id
					]
				);

			$product->update([
				'quantity' => $product->quantity + $validated['stock']
			]);

			return $product;
		});

		DB::transaction(function () use ($product, $validated) {
			$product->customizations()
				->firstOrCreate(
					[
						'name' => 'color'
					],
					[
						'options' => $validated['colors'],
						'enabled' => $validated['allowCustomColorSelection'],
						'product_id' => $product->id
					]
				);

			$product->customizations()
				->firstOrCreate(
					[
						'name' => 'name'
					],
					[
						'enabled' => $validated['allowCustomName'],
						'product_id' => $product->id
					]
				);

			$product->customizations()
				->firstOrCreate(
					[
						'name' => 'modality'
					],
					[
						'enabled' => $validated['allowCustomModality'],
						'product_id' => $product->id
					]
				);

			return $product;
		});

		/**
		 * @var string
		 */
		$message = 'Produto criado com sucesso!';

		if ($validated['stock'] > 1) {
			$message = 'Produtos criados com sucesso!';
		}

		return response()->json([
			'success' => true,
			'message' => $message
		], Response::HTTP_CREATED);
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
	public function update(UpdateProductRequest $request, Product $product)
	{
		Gate::authorize('update', Product::class);

		$validated = $request->validated();

		$shop = Auth::user()->shop;

		if (isset($validated['category'])) {
			/**
			 * @var \App\Models\Category
			 */
			$category = $shop->categories()
				->firstOrCreate(
					[
						'name' => $validated['category']
					],
					[
						'slug' => Str::slug($validated['category']),
						'shop_id' => $shop->id
					]
				);
		}

		return response()->json([
			'success' => true,
			'message' => 'Produto atualizado com sucesso'
		], Response::HTTP_CREATED);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Product $product)
	{
		Gate::authorize('delete', $product);

		$product->delete();

		return response()->json([
			'success' => true,
			'message' => 'Produto deletado com sucesso!'
		]);
	}
}
