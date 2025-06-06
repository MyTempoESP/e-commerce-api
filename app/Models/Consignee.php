<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consignee extends Model
{
	protected $fillable = [
		'name',
		'email',
		'phone',
		'document',
		'address'
	];

	public function address()
	{
		return $this->hasOne(Address::class);
	}

	public function consignments()
	{
		return $this->hasMany(Consignment::class);
	}
}
