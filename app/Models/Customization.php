<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customization extends Model
{
	protected $fillable = [
		'name',
		'options',
		'enabled',

		'sku_id'
	];

	protected function casts()
	{
		return [
			'options' => 'array'
		];
	}

	public function sku()
	{
		return $this->belongsTo(Sku::class);
	}
}
