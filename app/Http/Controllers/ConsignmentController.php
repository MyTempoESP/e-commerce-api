<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductToConsignmentRequest;
use App\Http\Requests\CreateConsignmentRequest;
use App\Models\Address;
use App\Models\Consignee;
use App\Models\Consignment;
use App\Models\Shop;
use App\Models\Sku;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ConsignmentController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		//
	}

	public function addProduct(
		AddProductToConsignmentRequest $request,
		Shop $shop,
		Consignment $consignment,
	) {
		Gate::authorize('addProduct', [$shop, $consignment]);

		$validated = $request->validated();

		// TODO: add locking
		$sku = $shop->skus()->findOrFail($validated['sku_id']);

		$req_quantity = $validated['quantity'];

		if ($sku->quantity < $req_quantity) {
			throw ValidationException::withMessages([
				'quantity' => 'Quantidade requisitada maior que estoque'
			]);
		}

		DB::transaction(function () use ($sku, $req_quantity, $consignment) {

			// TODO: WIP
			//$consignment->skus()->attach();

			$sku->quantity = $sku->quantity - $req_quantity;
			$sku->save();
		});
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
	public function store(CreateConsignmentRequest $request)
	{
		Gate::authorize('create', Consignment::class);

		try {
			$consignment = DB::transaction(
				function () use ($request) {
					$validated = $request->validated();

					$shop = Auth::user()->shop;

					// ensure consignee belongs to user's shop
					$consignee = $shop->consignees()->findOrFail(
						$validated['consignee_id']
					);

					$address = $consignee->address;

					if (isset($validated['address'])) {
						$address = Address::create(
							$validated['address']
						);
					}

					$consignment = Consignment::create([
						'name' => $validated['name'],
						'slug' => $validated['slug'],
						'status' => $validated['status'],
						'commission' => $validated['commission'],
						'commission_type' => $validated['commission_type'],

						'address_id' => $address->id,
						'consignee_id' => $consignee->id,
						'shop_id' => $shop->id
					]);

					return $consignment;
				}
			);

			return response()->json([
				'success' => true,
				'message' => 'Remessa criada com sucesso!',
				'remessa' => $consignment->toResource()
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
	public function update(Request $request, string $id)
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
