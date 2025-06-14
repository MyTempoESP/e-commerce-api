<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
			'code' => $this->code,
			'price' => $this->price,
			'stock' => $this->quantity,
			'discount' => $this->discount,
			'image' => $this->image,
			'description' => $this->description,
			'spec_images' => $this->spec_images,
			'desc_images' => $this->desc_images,
			'pack_images' => $this->pack_images,
			'featured' => $this->featured,
			'category' => $this->category->name
		];
	}
}
