<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
	protected $fillable = [
		'street_address',
		'locality',
		'region',
		'postal_code',
		'country'
	];

	public function shop()
	{
		return $this->belongsTo(Shop::class);
	}

	public function consignee()
	{
		return $this->belongsTo(Consignee::class);
	}
}
