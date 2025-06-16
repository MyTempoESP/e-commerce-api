<?php

namespace App\Models;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

// TODO: gerar SKU a partir da hash das especs do produto
class Product extends Model
{
	protected $fillable = [
		'uuid',
		'name',
		'code',

		'price',
		'quantity',

		'discount',

		'image',
		'description',

		'spec_images',
		'desc_images',
		'pack_images',

		'featured',

		'category_id',
		'shop_id'
	];

	protected function casts()
	{
		return [
			'spec_images' => 'array',
			'desc_images' => 'array',
			'pack_images' => 'array'
		];
	}

	public static function generateCode(array $fields): string
	{
		return $fields['name'] . $fields['category'] .
			'-' . collect($fields['spec'])
				->map(fn($spec) => $spec['value'])
				->implode('.');
	}

	public function consignments()
	{
		return $this->belongsToMany(Consignment::class, 'consignment_sku')
			->withPivot('price', 'quantity')
			->withTimestamps();
	}

	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function shop()
	{
		return $this->belongsTo(Shop::class);
	}

	public function customizations()
	{
		return $this->hasMany(Customization::class);
	}

	public function specifications()
	{
		return $this->hasMany(Specification::class);
	}
}
