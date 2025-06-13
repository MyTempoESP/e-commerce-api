<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	protected $fillable = [
		'uuid',
		'sku_id',
	];

	public function reports()
	{
		return $this->belongsToMany(Report::class)
			->withTimestamps();
	}

	public function sku()
	{
		$this->belongsTo(Sku::class);
	}
}
