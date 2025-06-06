<?php

namespace App\Http\Controllers;

use App\Models\Shop;

use App\Http\Requests\CreateShopRequest;
use App\Http\Requests\UpdateShopRequest;

class ShopController extends Controller
{

	private $shops;

	public function __construct(Shop $shop)
	{
		$this->shops = $shop;
	}

	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		return $this->shops->all()->toResourceCollection();
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
	public function store(CreateShopRequest $request)
	{
		$data = $request->all();

		$this->shops->create($data);

		return response()->json([
			'success' => true,
			'message' => 'Estabelecimento criado com sucesso!'
		]);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(string $id)
	{
		//
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
	public function update(UpdateShopRequest $request, string $id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(string $id)
	{
		//
	}
}
