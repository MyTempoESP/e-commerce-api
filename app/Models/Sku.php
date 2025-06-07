<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// TODO: gerar SKU a partir da hash das especs do produto
class Sku extends Model
{
	protected $table = 'skus';

	protected $fillable = [
		'code',
		'price',
		'quantity',

		'name',
		'slug',
		'image',
		'description',

		'category_id',
		'shop_id'
	];

	public function consignments()
	{
		return $this->belongsToMany(Consignment::class, 'consignment_sku')
			->withPivot('price', 'quantity')
			->withTimestamps();
	}

	public function category()
	{
		return $this->hasOne(Product::class);
	}

	public function shop()
	{
		return $this->belongsTo(Shop::class);
	}
}
