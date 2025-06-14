<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProductRequest extends FormRequest
{

	/*
	 * XXX: override failedValidation for custom response
	 * from https://stackoverflow.com/a/54462408
	 */
	protected function failedValidation(Validator $validator): void
	{
		$jsonResponse = response()->json(['errors' => $validator->errors()], 422);

		throw new HttpResponseException($jsonResponse);
	}

	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
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
			'price' => '',
			'image_url' => '', // url
			'category' => 'string',
			'discount' => '',
			'description' => 'max:300',
			'stock' => '',
			'descriptionImages' => '',
			'specificationsImages' => '',
			'deliveryImages' => '',
			'colors' => '',
			'allowCustomColorSelection' => '',
			'allowCustomName' => '',
			'allowCustomModality' => '',
			'featured' => '',
			'specifications' => '',
		];
	}
}
