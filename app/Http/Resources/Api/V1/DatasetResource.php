<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DatasetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'fintech_app_id' => $this->fintech_app_id,
            'name' => $this->name,
            'source' => $this->source,
            'total_rows' => $this->total_rows,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'fintech_app' => new FintechAppResource($this->whenLoaded('fintechApp')),
        ];
    }
}
