<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Report extends Model
{
	protected $fillable = [
		'report', // 'natureza' da ocorrência
		'type',
		'priority',
		'description',

		'consignee_id',
		'shop_id'
	];

	public function getTarget()
	{
		try {
			return $this->consignments()->firstOrFail();
		} catch (ModelNotFoundException) {
			return $this->products()->firstOrFail();
		}
	}

	public function consignments()
	{
		return $this->belongsToMany(Consignment::class)
			->withTimestamps();
	}

	public function products()
	{
		return $this->belongsToMany(Product::class)
			->withTimestamps();
	}

	public function shop()
	{
		return $this->belongsTo(Shop::class);
	}
}
