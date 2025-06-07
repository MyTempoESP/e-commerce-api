<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Models\Category;
use DB;
use Exception;
use Illuminate\Http\Request;
use Str;

class CategoryController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		return Category::all()->toResourceCollection();
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
	public function store(CreateCategoryRequest $request)
	{
		try {
			DB::transaction(function () use ($request) {
				$validated = $request->validated();

				$category = Category::create([
					'name' => $validated['name'],
					'slug' => Str::slug($validated['name']),
					'description' => $validated['description'] ?? '',
					'shop_id' => $validated['shop_id']
				]);

				return $category;
			});

			return response()->json([
				'success' => true,
				'message' => 'Categoria criada com sucesso!'
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
