<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * Returns just the application name as a string value.
     *
     * @return string
     */
    public function toArray(Request $request): string
    {
        return $this->name;
    }
}
