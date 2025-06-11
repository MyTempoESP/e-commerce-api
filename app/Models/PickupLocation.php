<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickupLocation extends Model
{
	protected $fillable = [
		'name',
		'slug',
		'pickup_at',

		'address_id',
		'shop_id'
	];

	public function shop()
	{
		return $this->belongsTo(Shop::class);
	}

	public function address()
	{
		return $this->belongsTo(Address::class);
	}

	public function consignments()
	{
		return $this->hasMany(Consignment::class);
	}
}
