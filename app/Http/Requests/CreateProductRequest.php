<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;

class CreateProductRequest extends FormRequest
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

		$specifications = [];

		$product = [
			'uuid' => Str::uuid(),
			'name' => $this->input('name'),
			'price' => $this->input('price'),
			'image' => $this->input('image_url'),
			'discount' => $this->input('discount'),
			'description' => $this->input('description'),
			'category' => $this->input('category'),
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

		if (
			$this->has('name') &&
			$this->has('specifications')
		) {
			$product['code'] = Product::generateCode(
				[
					'name' => $this->input('name'),
					'spec' => $this->input('specifications'),
					'category' => $this->input('category')
				]
			);
		}

		$data['quantity'] = $this->input('stock', null);
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
			'product.code' => 'required',
			'product.name' => 'required|string',
			'product.uuid' => 'required',
			'product.price' => 'required',
			'product.discount' => 'required',
			'product.description' => 'max:300',
			'product.image' => '', // url
			'product.desc_images' => '',
			'product.spec_images' => '',
			'product.pack_images' => '',
			'product.featured' => 'required',

			'category.name' => 'required|string',
			'category.slug' => 'required|string',

			'specifications' => 'required',

			'colors' => 'required',
			'quantity' => 'required',
			'allowCustomName' => 'required',
			'allowCustomModality' => 'required',
			'allowCustomColorSelection' => 'required',
		];
	}
}
