<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductToConsignmentRequest;
use App\Http\Requests\CreateConsignmentRequest;
use App\Models\Address;
use App\Models\Consignee;
use App\Models\Consignment;
use App\Models\Shop;
use App\Models\Sku;
use Illuminate\Support\Str;
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
		Gate::authorize('viewAny', Consignment::class);

		$shop = Auth::user()->shop;

		return $shop->consignments->toResourceCollection();
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

		$consignment = DB::transaction(
			function () use ($request) {
				$validated = $request->validated();

				$shop = Auth::user()->shop;

				// ensure consignee belongs to user's shop
				$consignee = $shop->consignees()->findOrFail(
					$validated['monitorId']
				);

				// ensure pl is on user's shop
				// TODO: should we create the pickup location?
				$pickupLocation = $shop->pickupLocations()->findOrFail(
					$validated['destinationId']
				);

				$commission = null;
				$commission_type = null;

				if (isset($validated['monitorProfitPercentage'])) {
					$commission_type = 'variable';
					$commission = $validated['monitorProfitPercentage'];
				} else {
					$commission_type = 'fixed';
					$commission = $validated['monitorProfit'];
				}

				$uuid = Str::uuid();

				$consignment = Consignment::create([
					'slug' => Str::slug($uuid),
					'uuid' => $uuid,

					'status' => $validated['status'],

					'commission' => $commission,
					'commission_type' => $commission_type,

					'pickup_location_id' => $pickupLocation->id,
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
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Consignment $consignment)
	{
		Gate::authorize('view', $consignment);

		return $consignment->toResource();
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
	public function update(Request $request, Consignment $consignment)
	{
		Gate::authorize('update', $consignment);

		$consignment = DB::transaction(
			function () use ($request, $consignment) {
				$validated = $request->validated();

				$shop = Auth::user()->shop;

				if (isset($validated['monitor'])) {
					// ensure consignee belongs to user's shop
					$consignee = $shop->consignees()->findOrFail(
						$validated['monitor']
					);

					$consignment->consignee_id = $consignee->id;
				}

				if (isset($validated['pickup_location_id'])) {
					// ensure pl is on user's shop
					// TODO: should we create the pickup location?
					$pickupLocation = $shop->pickupLocations()->findOrFail(
						$validated['pickup_location_id']
					);

					$consignment->pickup_location_id = $pickupLocation->id;
				}

				if (isset($validated['name'])) {
					$consignment->name = $validated['name'];
					$consignment->slug = Str::slug($validated['name']);
				}

				if (isset($validated['status'])) {
					$consignment->status = $validated['status'];
				}

				if (isset($validated['commission'])) {
					$consignment->commission = $validated['commission'];
					$consignment->commission_type = $validated['commission_type'];
				}

				$consignment->save();

				return $consignment;
			}
		);

		return response()->json([
			'success' => true,
			'message' => 'Remessa editada com sucesso!',
			'remessa' => $consignment->toResource()
		], 201);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(string $id)
	{
		//
	}
}
