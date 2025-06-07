<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	protected $fillable = [
		'sku_id',
	];

	public function sku()
	{
		$this->belongsTo(Sku::class);
	}
}
