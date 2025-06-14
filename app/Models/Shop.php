<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
	protected $fillable = [
		'name',
		'slug',
		'phone',
		'address_id',
		'user_id'
	];

	public function reports()
	{
		return $this->hasMany(Report::class);
	}

	public function products()
	{
		return $this->hasMany(Product::class);
	}

	public function pickupLocations()
	{
		return $this->hasMany(PickupLocation::class);
	}

	public function consignees()
	{
		return $this->hasMany(Consignee::class);
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
