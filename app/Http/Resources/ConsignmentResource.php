<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConsignmentResource extends JsonResource
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
			'slug' => $this->slug,
			'code' => $this->uuid,
			'status' => $this->status,
			'destination' => $this->pickupLocation->name,
			'responsibleMonitor' => $this->consignee->name,
			'commission_type' => $this->commission_type,
		];
	}
}
