<?php

namespace App\Http\Controllers;

use App\Models\Shop;

use App\Http\Requests\CreateShopRequest;
use App\Http\Requests\UpdateShopRequest;

use App\Models\Address;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

use Illuminate\Support\Str;

use Exception;

class ShopController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		Gate::authorize('viewAny', Shop::class);

		// TODO: learn dependency injection
		return Shop::all()->toResourceCollection();
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
		Gate::authorize('create', Shop::class);

		try {
			DB::transaction(function () use ($request) {
				$validated = $request->validated();

				$address = Address::create($validated['address']);

				$shop = Shop::create([
					'name' => $validated['name'],
					'slug' => Str::slug($validated['name']),

					'manager_first_name' => $validated['manager_first_name'],
					'manager_last_name' => $validated['manager_last_name'],

					'phone' => $validated['phone'],
					'address_id' => $address->id
				]);

				return $shop;
			});

			return response()->json([
				'success' => true,
				'message' => 'Estabelecimento criado com sucesso!'
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
		// TODO
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(string $id)
	{
		//
	}
}
