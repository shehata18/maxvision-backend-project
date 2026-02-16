<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * Returns the product in the format expected by the frontend listing.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $brightness = (int) round(($this->brightness_min + $this->brightness_max) / 2);

        return [
            'id' => $this->slug,
            'name' => $this->name,
            'series' => $this->series,
            'category' => $this->category,
            'image' => $this->image ? url('storage/' . $this->image) : null,
            'environment' => $this->environment,
            'brightness' => $brightness,
            'brightnessRange' => "{$this->brightness_min}-{$this->brightness_max} nits",
            'pixelPitch' => (float) $this->pixel_pitch,
            'pixelPitchLabel' => 'P' . rtrim(rtrim(number_format($this->pixel_pitch, 2), '0'), '.'),
            'cabinetSize' => $this->cabinet_size,
            'advantage' => $this->whenLoaded('features', function () {
                $first = $this->features->first();
                return $first ? $first->title : null;
            }),
            'price' => $this->price ?? 'Contact for Quote',
        ];
    }
}
