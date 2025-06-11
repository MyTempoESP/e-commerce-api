<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class CreatePickupLocationRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}

	protected function prepareForValidation()
	{
		$this->merge(
			[
				'address.cep' => Str::remove('-', $this->address['cep']),
			]
		);
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
			'datetime' => 'required|date',

			'address.street' => 'required|string',
			'address.city' => 'required|string',
			'address.neighborhood' => 'required|string',
			'address.state' => 'required|string',
			'address.cep' => 'required|string',
			'address.number' => 'required|integer',
			'address.complement' => 'string',
		];
	}
}
