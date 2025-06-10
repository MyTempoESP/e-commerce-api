<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateConsigneeRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return false;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'name' => 'required|string',
			'phone' => 'required|string',
			'email' => 'required|string',
			'cpf' => 'required|string',

			'address.street_address' => 'required|string',
			'address.locality' => 'required|string',
			'address.region' => 'required|string',
			'address.country' => 'required|string',
			'address.postal_code' => 'required|string'
		];
	}
}
