<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;

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

	public function prepareForValidation()
	{
		$data = [];

		$product = [
			'name' => $this->input('name'),
			'price' => $this->input('price'),
			'image' => $this->input('imageUrl'), // image_url in CREATE
			'discount' => $this->input('discount'),
			'description' => $this->input('description'),
			'category' => $this->input('category'),
			'quantity' => $this->input('stock'),
			'featured' => $this->input('featured'),

			'desc_images' => $this->input('descriptionImages', '[]'),
			'spec_images' => $this->input('specificationsImages', '[]'),
			'pack_images' => $this->input('deliveryImages', '[]'),
		];

		if ($this->has('category')) {
			$data['category'] =
				[
					'name' => $this->input('category'),
					'slug' => Str::slug($this->input('category')),
				];
		}

		$data['product'] = $product;

		$this->merge($data);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			// unchangeable
			//'product.code' => '',
			//'product.uuid' => '',

			'product.name' => '',
			'product.price' => '',
			'product.discount' => '',
			'product.description' => 'max:300',
			'product.image' => '',
			'product.desc_images' => '',
			'product.spec_images' => '',
			'product.pack_images' => '',
			'product.featured' => '',
			'product.quantity' => '',

			'category.name' => 'string',
			'category.slug' => 'string',

			// TODO
			//'colors' => '',
			//'allowCustomName' => '',
			//'allowCustomModality' => '',
			//'allowCustomColorSelection' => '',
		];
	}
}
