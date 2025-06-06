<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{

	private $shops;

	private $products;

	public function __construct(Product $product, Shop $shop)
	{
		$this->products = $product;
		$this->shops = $shop;
	}

	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$products = $this->products->all();

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
		$data = $request->all();

		$this->products->create($data);

		return response()->json([
			'success' => true,
			'message' => 'Produto criado com sucesso!'
		]);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(string $id)
	{
		$product = $this->products->findOrFail($id);

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
			$product = $this->products->findOrFail($id);
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
			$product = $this->products->findOrFail($id);
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
