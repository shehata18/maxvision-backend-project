<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * Returns the full product detail including specs, features, applications,
     * and related products from the same category.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $brightness = (int) round(($this->brightness_min + $this->brightness_max) / 2);

        // Build specs object from specifications relationship
        $specs = $this->whenLoaded('specifications', function () {
            $specsObj = [];
            foreach ($this->specifications as $spec) {
                $specsObj[$spec->spec_key] = $spec->spec_value;
            }

            // Always include core specs from the product model
            return array_merge([
                'pixelPitch' => $this->pixel_pitch . 'mm',
                'brightness' => "{$this->brightness_min}-{$this->brightness_max} nits",
                'cabinetSize' => $this->cabinet_size,
                'weight' => $this->weight,
                'power' => $this->power_consumption,
                'protection' => $this->protection_rating,
                'lifespan' => $this->lifespan,
                'operatingTemp' => $this->operating_temp,
            ], $specsObj);
        });

        // Get related products from same category
        $relatedProducts = $this->whenLoaded('features', function () {
            return Product::active()
                ->byCategory($this->category)
                ->where('id', '!=', $this->id)
                ->limit(4)
                ->pluck('slug')
                ->toArray();
        });

        return [
            'id' => $this->slug,
            'name' => $this->name,
            'series' => $this->series,
            'category' => $this->category,
            'tagline' => $this->tagline,
            'description' => $this->description,
            'image' => $this->image_url,
            'imageResponsive' => $this->image_responsive,
            'gallery' => $this->gallery_urls,
            'galleryResponsive' => $this->gallery_responsive,
            'environment' => $this->environment,
            'brightness' => $brightness,
            'brightnessRange' => "{$this->brightness_min}-{$this->brightness_max} nits",
            'pixelPitch' => (float) $this->pixel_pitch,
            'pixelPitchLabel' => 'P' . rtrim(rtrim(number_format($this->pixel_pitch, 2), '0'), '.'),
            'cabinetSize' => $this->cabinet_size,
            'price' => $this->price ?? 'Contact for Quote',
            'specs' => $specs,
            'features' => ProductFeatureResource::collection($this->whenLoaded('features')),
            'applications' => $this->whenLoaded('applications', function () {
                return $this->applications->pluck('name')->toArray();
            }),
            'relatedProducts' => $relatedProducts,
            'specsPdfUrl' => $this->specs_pdf_url,
            'datasheetPdfUrl' => $this->datasheet_pdf_url,
            'cadDrawingsUrl' => $this->cad_drawings_url,
        ];
    }
}
