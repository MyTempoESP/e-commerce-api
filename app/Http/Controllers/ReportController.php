<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReportRequest;
use App\Models\Report;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class ReportController extends Controller
{

	public function index()
	{
		Gate::authorize('viewAny', Report::class);

		/**
		 * @var \App\Models\Shop
		 */
		$shop = Auth::user()->shop;

		return $shop->reports->toResourceCollection();
	}

	public function store(CreateReportRequest $request)
	{
		Gate::authorize('create', Report::class);

		DB::transaction(function () use ($request) {
			$validated = $request->validated();

			/**
			 * @var \App\Models\Shop
			 */
			$shop = Auth::user()->shop;

			/**
			 * @var \App\Models\Consignee
			 */
			$consignee = $shop->consignees()->where(
				'name',
				$validated['monitor']
			)->firstOrFail();

			$report = Report::create([
				'report' => $validated['report'],
				'type' => $validated['type'],
				'priority' => $validated['priority'],
				'description' => $validated['description'],

				'consignee_id' => $consignee->id,
				'shop_id' => $shop->id
			]);

			try {
				/**
				 * @var \App\Models\Consignment
				 */
				$consignment = $shop->where(
					'uuid',
					$validated['uuid']
				)->firstOrFail();

				$consignment->reports()->attach(
					$report->id
				);
			} catch (ModelNotFoundException) {
				/**
				 * @var \App\Models\Sku
				 */
				$sku = $shop->skus()->where(
					'uuid',
					$validated['uuid']
				)->firstOrFail();

				if (!$sku->consignments()->exists()) {
					throw ValidationException::withMessages(
						[
							'sku' => 'este produto não está em uma remessa'
						]
					);
				}

				$sku->reports()->attach($report->id);
			}
		});
	}
}
