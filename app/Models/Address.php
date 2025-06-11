<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
	protected $fillable = [
		'number',
		'street',
		'neighborhood',
		'city',
		'state',
		'cep'
	];

	public function pickupLocations()
	{
		return $this->hasOne(PickupLocation::class);
	}

	public function consignment()
	{
		return $this->hasOne(Consignment::class);
	}

	public function shop()
	{
		return $this->hasOne(Shop::class);
	}

	public function consignee()
	{
		return $this->hasOne(Consignee::class);
	}
}
