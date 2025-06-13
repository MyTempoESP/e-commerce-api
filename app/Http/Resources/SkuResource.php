<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SkuResource extends JsonResource
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
			'code' => $this->code,
			'price' => $this->price,
			'quantity' => $this->quantity,
			'discount' => $this->discount,
			'image' => $this->image,
			'description' => $this->description,
			'spec_images' => $this->spec_images,
			'desc_images' => $this->desc_images,
			'pack_images' => $this->pack_images,
			'featured' => $this->featured,
			'category' => $this->category->toResource(),
			'shop' => $this->shop->toResource()
		];
	}
}
