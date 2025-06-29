<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specification extends Model
{
	protected $fillable = [
		'name',
		'value',
		'product_id'
	];

	public function product()
	{
		return $this->belongsTo(Product::class);
	}
}
