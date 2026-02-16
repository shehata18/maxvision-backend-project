<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SolutionDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * Returns the full solution detail including benefits, specs,
     * and recommended products with pivot data.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->slug,
            'title' => $this->title,
            'tagline' => $this->tagline,
            'description' => $this->description,
            'category' => $this->category,
            'image' => $this->image ? url('storage/' . $this->image) : null,
            'benefits' => $this->whenLoaded('benefits', function () {
                return $this->benefits->pluck('benefit_text')->toArray();
            }),
            'specs' => $this->whenLoaded('specs', function () {
                return $this->specs->map(fn ($spec) => [
                    'label' => $spec->label,
                    'value' => $spec->value,
                ])->toArray();
            }),
            'recommendedProducts' => $this->whenLoaded('recommendedProducts', function () {
                return $this->recommendedProducts
                    ->sortBy('pivot.order')
                    ->map(fn ($product) => [
                        'name' => $product->pivot->display_name,
                        'series' => $product->pivot->series,
                        'pitch' => $product->pivot->pitch,
                        'brightness' => $product->pivot->brightness,
                        'href' => '/products/' . $product->slug,
                    ])->values()->toArray();
            }),
        ];
    }
}
