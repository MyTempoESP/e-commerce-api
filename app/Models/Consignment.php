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

		'address_id',
		'consignee_id',
		'shop_id'
	];

	public function address()
	{
		return $this->belongsTo(Address::class);
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
