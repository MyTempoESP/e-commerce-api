<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
	protected $fillable = [
		'name',
		'email',
		'phone',
		'document',
		'address'
	];

	public function products()
	{
		return $this->hasMany(Product::class);
	}

	public function consignments()
	{
		return $this->hasMany(Consignment::class);
	}

	public function categories()
	{
		return $this->hasMany(Category::class);
	}

	// NOTE: possibly many?
	public function address()
	{
		return $this->hasOne(Address::class);
	}
}
