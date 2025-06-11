<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PermissionController extends Controller
{

	public function accessLevel(Request $request)
	{
		if (Auth::user()->administrator()->exists()) {
			return response()->json([
				'level' => 'admin'
			]);
		}

		if (Auth::user()->shop()->exists()) {
			return response()->json([
				'level' => 'estabelecimento'
			]);
		}

		if (Auth::user()->consignee()->exists()) {
			return response()->json([
				'level' => 'monitor'
			]);
		}

		return response()->json([
			'level' => 'none'
		]);
	}

	public function admin(Request $request)
	{
		Gate::authorize('allowedAdmin', User::class);
		return response("Permitido");
	}

	public function shop(Request $request)
	{
		Gate::authorize('allowedShop', User::class);
		return response("Permitido");
	}

	public function consignee(Request $request)
	{
		Gate::authorize('allowedConsignee', User::class);
		return response("Permitido");
	}
}
