<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
	public function login(LoginRequest $request)
	{
		$credentials = $request->validated();

		if (Auth::attempt($credentials)) {

			$user = Auth::user();

			return response()->json([
				'success' => true,
				'user' => $user->toResource()
			]);
		}

		throw ValidationException::withMessages([
			'email' => 'Credenciais inválidas',
		]);
	}

	public function logout(Request $request)
	{
		Auth::logout();

		return response()->json([
			'success' => true
		]);
	}
}
