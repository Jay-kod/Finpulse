<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'dataset_id' => $this->dataset_id,
            'author_name' => $this->author_name,
            'rating' => $this->rating,
            'review_text' => $this->review_text,
            'posted_at' => $this->posted_at,
            'sentiment_score' => $this->sentiment_score,
            'sentiment_label' => $this->sentiment_label,
            'language' => $this->language,
            'created_at' => $this->created_at,
            'dataset' => new DatasetResource($this->whenLoaded('dataset')),
        ];
    }
}
