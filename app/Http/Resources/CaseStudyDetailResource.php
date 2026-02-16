<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CaseStudyDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * Returns the full case study detail including metrics, specs,
     * and products used in the project.
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
            'image' => $this->image ? url('storage/' . $this->image) : null,
            'description' => $this->description,
            'challenge' => $this->challenge,
            'solution' => $this->solution,
            'is_featured' => $this->is_featured,
            'metrics' => $this->whenLoaded('metrics', function () {
                return $this->metrics->map(fn ($metric) => [
                    'label' => $metric->label,
                    'value' => $metric->value,
                    'icon' => $metric->icon,
                ])->toArray();
            }),
            'specs' => $this->whenLoaded('specs', function () {
                return $this->specs->map(fn ($spec) => [
                    'label' => $spec->label,
                    'value' => $spec->value,
                ])->toArray();
            }),
            'products' => $this->whenLoaded('products', function () {
                return $this->products->map(fn ($product) => [
                    'name' => $product->pivot->product_name ?? $product->name,
                    'slug' => $product->slug,
                    'href' => '/products/' . $product->slug,
                ])->toArray();
            }),
        ];
    }
}
