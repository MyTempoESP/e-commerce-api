<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateConsignmentRequest extends FormRequest
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
			'name' => 'required',
			'slug' => 'required',
			'status' => 'required',
			'commission' => 'required',
			'commission_type' => 'required',
			'consignee_id' => 'required',

			'address.street_address' => 'string',
			'address.locality' => 'string',
			'address.region' => 'string',
			'address.country' => 'string',
			'address.postal_code' => 'string'
		];
	}
}
