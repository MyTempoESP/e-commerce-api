<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateConsigneeRequest extends FormRequest
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
			'name' => 'string',
			'phone' => 'string',
			'email' => 'string',

			'address.street' => 'required_with:address|string',
			'address.city' => 'required_with:address|string',
			'address.neighborhood' => 'required_with:address|string',
			'address.state' => 'required_with:address|string',
			'address.cep' => 'required_with:address|string',
			'address.number' => 'required_with:address|integer',
		];
	}
}
