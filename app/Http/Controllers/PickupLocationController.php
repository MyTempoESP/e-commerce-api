<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePickupLocationRequest;
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

			$address = Address::create(
				[
					'street_address' =>
						$address_info['street'] .
						', ' .
						$address_info['number']
					,
					'locality' =>
						$address_info['neighborhood'] .
						' - ' .
						$address_info['city']
					,
					'region' => $address_info['state'],
					'postal_code' => Str::remove(
						'-',
						$address_info['cep']
					),
					'complement' => $address_info['complement'] ?? '',
					'country' => 'BR'
				]
			);

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
}
