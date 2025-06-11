<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class AddressResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'street' => $this->street,
			'number' => $this->number,
			'city' => $this->city,
			'neighborhood' => $this->neighborhood,
			'state' => $this->state,
			'cep' => $this->cep,
			'complement' => $this->complement
		];
	}
}
