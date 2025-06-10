<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consignee extends Model
{
	protected $fillable = [
		'name',
		'email',
		'phone',

		'shop_id',
		'address_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function shop()
	{
		return $this->belongsTo(Shop::class);
	}

	public function consignments()
	{
		return $this->belongsTo(Consignment::class);
	}

	public function address()
	{
		return $this->belongsTo(Address::class);
	}
}
