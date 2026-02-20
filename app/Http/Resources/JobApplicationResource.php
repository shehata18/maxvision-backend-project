<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'jobListingId' => $this->job_listing_id,
            'jobTitle' => $this->jobListing?->title,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'fullName' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'coverLetter' => $this->cover_letter,
            'resumeUrl' => $this->resume_url,
            'resumeOriginalName' => $this->resume_original_name,
            'linkedinUrl' => $this->linkedin_url,
            'portfolioUrl' => $this->portfolio_url,
            'status' => $this->status,
            'statusLabel' => $this->status_label,
            'statusColor' => $this->status_color,
            'notes' => $this->notes,
            'isGeneralApplication' => $this->is_general_application,
            'reviewedAt' => $this->reviewed_at?->toISOString(),
            'createdAt' => $this->created_at->toISOString(),
        ];
    }
}
