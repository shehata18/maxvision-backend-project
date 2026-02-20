<?php

namespace App\Models;

use App\Enums\JobApplicationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * JobApplication Model
 *
 * Represents a job application submission.
 *
 * @property int $id
 * @property int|null $job_listing_id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string|null $phone
 * @property string|null $cover_letter
 * @property string|null $resume_path
 * @property string|null $resume_original_name
 * @property string|null $linkedin_url
 * @property string|null $portfolio_url
 * @property string $status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $reviewed_at
 * @property int|null $reviewed_by
 * @property bool $is_general_application
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read JobListing|null $jobListing
 * @property-read User|null $reviewer
 *
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication pending() Scope for pending applications
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication byStatus(string $status) Scope to filter by status
 */
class JobApplication extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'job_listing_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'cover_letter',
        'resume_path',
        'resume_original_name',
        'linkedin_url',
        'portfolio_url',
        'status',
        'notes',
        'reviewed_at',
        'reviewed_by',
        'is_general_application',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'reviewed_at' => 'datetime',
        'is_general_application' => 'boolean',
    ];

    /**
     * Get the job listing this application is for.
     *
     * @return BelongsTo<JobListing, JobApplication>
     */
    public function jobListing(): BelongsTo
    {
        return $this->belongsTo(JobListing::class);
    }

    /**
     * Get the user who reviewed this application.
     *
     * @return BelongsTo<User, JobApplication>
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Scope a query to only include pending applications.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', JobApplicationStatus::Pending->value);
    }

    /**
     * Scope a query to filter by status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include general applications (not for specific job).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGeneralApplications($query)
    {
        return $query->where('is_general_application', true);
    }

    /**
     * Scope a query to only include job-specific applications.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeJobSpecific($query)
    {
        return $query->where('is_general_application', false);
    }

    /**
     * Get the applicant's full name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the full URL for the resume.
     */
    public function getResumeUrlAttribute(): ?string
    {
        return $this->resume_path ? asset('storage/' . $this->resume_path) : null;
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute(): string
    {
        $status = $this->status ?? 'pending';
        return JobApplicationStatus::tryFrom($status)?->label() ?? ucfirst($status);
    }

    /**
     * Get the status color.
     */
    public function getStatusColorAttribute(): string
    {
        $status = $this->status ?? 'pending';
        return JobApplicationStatus::tryFrom($status)?->color() ?? 'gray';
    }

    /**
     * Check if the application is for a specific job.
     */
    public function isForSpecificJob(): bool
    {
        return !$this->is_general_application && $this->job_listing_id !== null;
    }

    /**
     * Mark the application as reviewed.
     */
    public function markAsReviewed(int $userId, ?string $status = null): void
    {
        $this->update([
            'reviewed_at' => now(),
            'reviewed_by' => $userId,
            'status' => $status ?? JobApplicationStatus::Reviewing->value,
        ]);
    }
}
