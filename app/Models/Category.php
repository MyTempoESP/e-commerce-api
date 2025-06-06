<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	protected $fillable = [
		'name',
		'description',
		'shop_id'
	];

	public function product()
	{
		return $this->hasMany(Product::class);
	}

	public function shop()
	{
		return $this->belongsTo(Shop::class);
	}
}
