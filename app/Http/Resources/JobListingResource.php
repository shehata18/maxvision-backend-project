<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobListingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->slug,
            'title' => $this->title,
            'department' => $this->department,
            'location' => $this->location,
            'locationLabel' => $this->location_label,
            'jobType' => $this->job_type,
            'jobTypeLabel' => $this->job_type_label,
            'category' => $this->category,
            'categoryLabel' => $this->category_label,
            'summary' => $this->summary,
            'salaryRange' => $this->salary_range,
            'postedAt' => $this->posted_at?->format('Y-m-d'),
            'deadline' => $this->deadline?->format('Y-m-d'),
            'isFeatured' => $this->is_featured,
            'isExpired' => $this->isExpired(),
        ];
    }
}
