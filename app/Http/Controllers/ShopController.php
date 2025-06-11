<?php

namespace App\Http\Controllers;

use App\Models\Shop;

use App\Http\Requests\CreateShopRequest;
use App\Http\Requests\UpdateShopRequest;

use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Exception;
use Illuminate\Validation\ValidationException;

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

		DB::transaction(function () use ($request) {
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
						$address_info['city'],
					'region' => $address_info['state'],
					'postal_code' => Str::remove(
						'-',
						$address_info['cep']
					),
					'complement' => $address_info['complement'] ?? '',
					'country' => 'BR'
				]
			);

			$user = User::firstOrCreate(
				[
					'email' => $validated['email'],
				],
				[
					'name' => $validated['name'],
					'password' => Hash::make($validated['telephone'])
				]
			);

			if ($user->shop()->exists()) {
				throw ValidationException::withMessages([
					'user' => 'Usuário já possui um estabelecimento'
				]);
			}

			$shop = Shop::create([
				'name' => $validated['name'],
				'slug' => Str::slug($validated['name']),
				'phone' => $validated['telephone'],

				'address_id' => $address->id,
				'user_id' => $user->id
			]);

			return $shop;
		});

		return response()->json([
			'success' => true,
			'message' => 'Estabelecimento criado com sucesso!'
		], 201);
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
