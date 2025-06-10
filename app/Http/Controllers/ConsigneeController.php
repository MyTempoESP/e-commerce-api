<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateConsigneeRequest;
use App\Models\Address;
use App\Models\User;
use DB;
use Exception;
use Illuminate\Http\Request;

use App\Models\Consignee;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ConsigneeController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		//
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

		try {
			DB::transaction(function () use ($request) {
				$validated = $request->validated();

				$address = Address::create($validated['address']);

				$user = User::create([
					'name' => $validated['name'],
					'email' => $validated['email'],
					'password' => Hash::make($validated['cpf'])
				]);

				$consignee = Consignee::create([
					'name' => $validated['name'],
					'phone' => $validated['phone'],

					'shop_id' => Auth::user()->shop->id,
					'address_id' => $address->id,
					'user_id' => $user->id
				]);
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
