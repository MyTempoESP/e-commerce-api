<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consignment extends Model
{
	protected $fillable = [
		'name',
		'slug',
		'status',
		'commission',
		'commission_type',
		'uuid',

		'pickup_location_id',
		'consignee_id',
		'shop_id'
	];

	public function reports()
	{
		return $this->belongsToMany(Report::class)
			->withTimestamps();
	}

	public function pickupLocation()
	{
		return $this->belongsTo(PickupLocation::class);
	}

	public function products()
	{
		return $this->belongsToMany(Product::class)
			->withPivot('price', 'quantity')
			->withTimestamps();
	}

	public function consignee()
	{
		return $this->belongsTo(Consignee::class);
	}

	public function shop()
	{
		return $this->belongsTo(Shop::class);
	}
}
