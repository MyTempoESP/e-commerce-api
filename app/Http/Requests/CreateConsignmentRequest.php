<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class CreateConsignmentRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}

	public function prepareForValidation()
	{
		$this->merge(
			[
				'status' => Str::lower($this->status)
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
			'status' => 'required|string',
			'monitorId' => 'required',
			'monitorProfit' => 'required_without:monitorProfitPercentage',
			'monitorProfitPercentage' => 'required_without:monitorProfit',
			'destinationId' => 'required',
		];
	}
}
