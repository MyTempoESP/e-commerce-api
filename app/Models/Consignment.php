<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consignment extends Model
{
	protected $fillable = [
		'slug',
		'name',
		'description',
		'commission'
		// TODO: 'consignee',
		// TODO: 'shop'
	];
}
