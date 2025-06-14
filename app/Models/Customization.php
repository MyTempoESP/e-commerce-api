<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customization extends Model
{
	protected $fillable = [
		'name',
		'options',
		'enabled',

		'product_id'
	];

	protected function casts()
	{
		return [
			'options' => 'array'
		];
	}

	public function product()
	{
		return $this->belongsTo(Product::class);
	}
}
