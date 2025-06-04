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
}
