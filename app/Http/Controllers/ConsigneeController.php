<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateConsigneeRequest;
use App\Http\Requests\UpdateConsigneeRequest;

use App\Models\Consignee;
use App\Models\Address;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ConsigneeController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		Gate::authorize('viewAny', Consignee::class);

		return Auth::user()->shop->consignees->toResourceCollection();
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(CreateConsigneeRequest $request)
	{
		Gate::authorize('create', Consignee::class);

		$consignee = DB::transaction(function () use ($request) {
			$validated = $request->validated();

			$address = Address::create($validated['address']);

			$user = User::firstOrCreate(
				[
					'email' => $validated['email'],
				],
				[
					'name' => $validated['name'],
					'password' => Hash::make($validated['phone'])
				]
			);

			$consignee = Consignee::create([
				'name' => $validated['name'],
				'phone' => $validated['phone'],

				'shop_id' => Auth::user()->shop->id,
				'address_id' => $address->id,
				'user_id' => $user->id
			]);

			return $consignee;
		});

		return response()->json([
			'success' => true,
			'message' => 'Monitor criado com sucesso!',
			'monitor' => $consignee->toResource()
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
	public function update(UpdateConsigneeRequest $request, Consignee $consignee)
	{
		Gate::authorize('update', $consignee);

		$consignee = DB::transaction(function () use ($request, $consignee) {
			$validated = $request->validated();

			if (isset($validated['address'])) {
				$address = $consignee->address;

				$address_info = $validated['address'];

				$address->update($address_info);
			}

			if (isset($validated['name'])) {
				$consignee->name = $validated['name'];
			}

			if (isset($validated['phone'])) {
				$consignee->phone = $validated['phone'];
			}

			$consignee->save();

			return $consignee;
		});

		return response()->json([
			'success' => true,
			'message' => 'Monitor editado com sucesso!',
			'monitor' => $consignee->toResource()
		], 201);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Consignee $consignee)
	{
		Gate::authorize('delete', $consignee);

		DB::transaction(function () use ($consignee) {
			$address = $consignee->address;
			$user = $consignee->user;

			$consignee->delete();
			$address->delete();
			$user->delete();
		});

		return response()->json([
			'success' => true,
			'message' => 'Monitor deletado com sucesso!'
		]);
	}
}
