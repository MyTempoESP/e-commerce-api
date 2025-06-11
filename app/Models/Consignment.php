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

		'pickup_location_id',
		'consignee_id',
		'shop_id'
	];

	public function pickupLocation()
	{
		return $this->belongsTo(PickupLocation::class);
	}

	public function skus()
	{
		return $this->belongsToMany(Sku::class, 'consignment_sku')
			->withPivot('price', 'quantity')
			->withTimestamps();
	}

	public function consignee()
	{
		return $this->hasOne(Consignee::class);
	}

	public function shop()
	{
		return $this->belongsTo(Shop::class);
	}
}
