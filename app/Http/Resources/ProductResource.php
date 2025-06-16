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
			'imageUrl' => $this->image,
			'description' => $this->description,
			'specificationsImages' => $this->spec_images,
			'descriptionImages' => $this->desc_images,
			'deliveryImages' => $this->pack_images,
			'featured' => $this->featured,
			'category' => $this->category->name,
			'specifications' => $this->specifications->toResourceCollection(),
			'colors' => $this->customizations()->where(
				'name',
				'color'
			)->first()->toResource(),
		];
	}
}
