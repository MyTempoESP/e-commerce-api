<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;

use App\Models\Consignment;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Specification;
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

		$category_details = $validated['category'];
		$category_details['shop_id'] = $shop->id;

		/**
		 * @var \App\Models\Category
		 * TODO: fetch by id or name?
		 */
		$category = $shop->categories()
			->firstOrCreate(
				[
					'name' => $category_details['name']
				],
				$category_details
			);

		/**
		 * @var Product
		 */
		$product = DB::transaction(function () use ($validated, $shop, $category) {
			$product_details = $validated['product'];

			$product_details['category_id'] = $category->id;
			$product_details['shop_id'] = $shop->id;

			// TODO: remove
			$product_details['quantity'] = 0;

			/**
			 * @var Product
			 */
			$product = $shop->products()->lockForUpdate()
				->firstOrCreate(
					[
						'code' => $product_details['code']
					],
					$product_details
				);

			$product->update([
				'quantity' => $product->quantity + $validated['quantity']
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

		DB::transaction(function () use ($product, $validated) {
			foreach ($validated['specifications'] as $spec) {
				$product->specifications()->firstOrCreate(
					[
						'name' => $spec['name']
					],
					[
						'value' => $spec['value'],
						'product_id' => $product->id
					]
				);
			}
		});

		/**
		 * @var string
		 */
		$message = 'Produto criado com sucesso!';

		if ($validated['quantity'] > 1) {
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
		Gate::authorize('update', $product);

		$validated = $request->validated();

		$shop = Auth::user()->shop;

		$category_details = $validated['category'];
		$category_details['shop_id'] = $shop->id;

		/**
		 * @var \App\Models\Category
		 * TODO: fetch by id or name?
		 */
		$category = $shop->categories()
			->firstOrCreate(
				[
					'name' => $category_details['name']
				],
				$category_details
			);

		DB::transaction(function () use ($product, $validated, $category) {

			$product_details = $validated['product'];
			$product_details['category_id'] = $category->id;

			$product->update($product_details);
		});

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
