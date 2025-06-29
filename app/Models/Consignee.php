<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consignee extends Model
{
	protected $fillable = [
		'name',
		'phone',

		'shop_id',
		'address_id',
		'user_id'
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
		return $this->hasMany(Consignment::class);
	}

	public function address()
	{
		return $this->belongsTo(Address::class);
	}
}
