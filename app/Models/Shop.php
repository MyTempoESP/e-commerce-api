<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
	protected $fillable = [
		'name',
		'slug',
		'manager_first_name',
		'manager_last_name',
		'phone',
		'address_id'
	];

	public function skus()
	{
		return $this->hasMany(Sku::class);
	}

	public function consignments()
	{
		return $this->hasMany(Consignment::class);
	}

	public function categories()
	{
		return $this->hasMany(Category::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	// NOTE: possibly many?
	public function address()
	{
		return $this->belongsTo(Address::class);
	}
}
