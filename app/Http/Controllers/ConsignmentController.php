<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductToConsignmentRequest;
use App\Http\Requests\CreateConsignmentRequest;
use App\Http\Requests\UpdateConsignmentRequest;
use App\Models\Address;
use App\Models\Consignee;
use App\Models\Consignment;
use App\Models\Shop;
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
		Consignment $consignment,
	) {
		Gate::authorize('addProduct', $consignment);

		$shop = Auth::user()->shop;

		$validated = $request->validated();

		DB::transaction(function () use ($shop, $consignment, $validated) {

			$product = $shop->products()->lockForUpdate()->findOrFail($validated['product_id']);

			$req_quantity = $validated['quantity'];

			if ($product->quantity < $req_quantity) {
				throw ValidationException::withMessages([
					'quantity' => 'Quantidade requisitada maior que estoque'
				]);
			}

			$product->quantity = $product->quantity - $req_quantity;

			$product->save();

			$consignment->products()->toggle(
				[
					$product->id => [
						'quantity' => $req_quantity,
						'price' => $product->price,
					]
				]
			);
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
	public function update(UpdateConsignmentRequest $request, Consignment $consignment)
	{
		Gate::authorize('update', $consignment);

		$consignment = DB::transaction(
			function () use ($request, $consignment) {
				$validated = $request->validated();

				$shop = Auth::user()->shop;

				if (isset($validated['monitorId'])) {
					// ensure consignee belongs to user's shop
					$consignee = $shop->consignees()->findOrFail(
						$validated['monitorId']
					);

					$consignment->consignee_id = $consignee->id;
				}

				if (isset($validated['destinationId'])) {
					// ensure pl is on user's shop
					// TODO: should we create the pickup location?
					$pickupLocation = $shop->pickupLocations()->findOrFail(
						$validated['destinationId']
					);

					$consignment->pickup_location_id = $pickupLocation->id;
				}

				if (isset($validated['status'])) {
					$consignment->status = $validated['status'];
				}

				if (isset($validated['monitorProfitPercentage'])) {
					$consignment->commission = $validated['monitorProfitPercentage'];
					$consignment->commission_type = 'variable';
				} else if (isset($validated['monitorProfit'])) {
					$consignment->commission = $validated['monitorProfit'];
					$consignment->commission_type = 'fixed';
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
	public function destroy(Consignment $consignment)
	{
		Gate::authorize('delete', $consignment);

		$consignment->delete();
	}
}
