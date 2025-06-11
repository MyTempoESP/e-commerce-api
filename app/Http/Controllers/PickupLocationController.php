<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePickupLocationRequest;
use App\Http\Requests\UpdatePickupLocationRequest;
use App\Models\Address;
use App\Models\PickupLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class PickupLocationController extends Controller
{
	public function index(Request $request)
	{
		Gate::authorize('viewAny', PickupLocation::class);

		return Auth::user()->shop->pickupLocations->toResourceCollection();
	}

	public function store(CreatePickupLocationRequest $request)
	{
		Gate::authorize('addPickupLocation', PickupLocation::class);

		$location = DB::transaction(function () use ($request) {
			$validated = $request->validated();

			$address_info = $validated['address'];
			$address_info['cep'] = Str::remove('-', $address_info['cep']);

			$address = Address::create($address_info);

			$shop = Auth::user()->shop;

			$location = PickupLocation::create([
				'name' => $validated['name'],
				'slug' => Str::slug($validated['name']),
				'pickup_at' => new Carbon($validated['datetime']),

				'shop_id' => $shop->id,
				'address_id' => $address->id
			]);

			return $location;
		});

		return response()->json([
			'success' => true,
			'message' => 'Local de retirada criado com sucesso!',
			'ponto_retirada' => $location->toResource()
		]);
	}

	public function update(UpdatePickupLocationRequest $request, PickupLocation $pickupLocation)
	{
		Gate::authorize('update', $pickupLocation);

		DB::transaction(function () use ($request, $pickupLocation) {
			$validated = $request->validated();

			if (isset($validated['address'])) {
				$address = $pickupLocation->address;

				$address_info = $validated['address'];
				$address_info['cep'] = Str::remove('-', $address_info['cep']);

				$address->update($address_info);
			}

			if (isset($validated['name'])) {
				$pickupLocation->name = $validated['name'];
				$pickupLocation->slug = Str::slug($validated['name']);
			}

			if (isset($validated['datetime'])) {
				$pickupLocation->pickup_at = new Carbon($validated['datetime']);
			}

			$pickupLocation->save();
		});

		return response()->json([
			'success' => true,
			'message' => 'Local de retirada criado com sucesso!',
			'ponto_retirada' => $pickupLocation->toResource()
		]);
	}

	public function destroy(PickupLocation $pickupLocation)
	{
		Gate::authorize('delete', $pickupLocation);

		DB::transaction(function () use ($pickupLocation) {
			$address = $pickupLocation->address;

			$pickupLocation->delete();
			$address->delete();
		});

		return response()->json([
			'success' => true,
			'message' => 'Local de retirada deletado com sucesso!'
		]);
	}
}
