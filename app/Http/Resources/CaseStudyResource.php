<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CaseStudyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * Returns the case study in the listing format expected by the frontend.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->slug,
            'title' => $this->title,
            'client' => $this->client,
            'industry' => $this->industry,
            'location' => $this->location,
            'date' => $this->date,
            'image' => $this->image_url,
            'imageResponsive' => $this->image_responsive,
            'description' => $this->description,
            'is_featured' => $this->is_featured,
            'metrics' => $this->whenLoaded('metrics', function () {
                return $this->metrics->take(2)->map(fn ($metric) => [
                    'label' => $metric->label,
                    'value' => $metric->value,
                    'icon' => $metric->icon,
                ])->toArray();
            }),
        ];
    }
}
