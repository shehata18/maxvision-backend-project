<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class SolutionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * Returns the solution in the listing format expected by the frontend.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->slug,
            'title' => $this->title,
            'tagline' => $this->tagline,
            'category' => $this->category,
            'image' => $this->image ? url('storage/' . $this->image) : null,
            'description' => $this->description ? Str::limit($this->description, 200) : null,
        ];
    }
}
