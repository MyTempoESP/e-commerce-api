<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
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
			'report' => $this->report,
			'type' => $this->type,
			'priority' => $this->priority,
			'description' => $this->description,

			'monitor' => $this->consignee->name,
			'idcoisa' => $this->getTarget()->uuid
		];
	}
}
