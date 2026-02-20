<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * JobListing Model
 *
 * Represents a job opening/career opportunity.
 *
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string $department
 * @property string $location
 * @property string $job_type
 * @property string $category
 * @property string $summary
 * @property string|null $description
 * @property array|null $requirements
 * @property array|null $benefits
 * @property string|null $salary_range
 * @property \Illuminate\Support\Carbon|null $posted_at
 * @property \Illuminate\Support\Carbon|null $deadline
 * @property bool $is_active
 * @property bool $is_featured
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|JobListing active() Scope to filter only active jobs
 * @method static \Illuminate\Database\Eloquent\Builder|JobListing featured() Scope to filter featured jobs
 * @method static \Illuminate\Database\Eloquent\Builder|JobListing byCategory(string $category) Scope to filter by category
 * @method static \Illuminate\Database\Eloquent\Builder|JobListing byDepartment(string $department) Scope to filter by department
 * @method static \Illuminate\Database\Eloquent\Builder|JobListing byLocation(string $location) Scope to filter by location
 * @method static \Illuminate\Database\Eloquent\Builder|JobListing byType(string $type) Scope to filter by job type
 */
class JobListing extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'title',
        'department',
        'location',
        'job_type',
        'category',
        'summary',
        'description',
        'requirements',
        'benefits',
        'salary_range',
        'posted_at',
        'deadline',
        'is_active',
        'is_featured',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'requirements' => 'array',
        'benefits' => 'array',
        'posted_at' => 'date',
        'deadline' => 'date',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * Boot the model.
     * Auto-generates slug from title if not provided.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (JobListing $job) {
            if (empty($job->slug)) {
                $job->slug = Str::slug($job->title . '-' . Str::random(6));
            }
            if (empty($job->posted_at)) {
                $job->posted_at = now();
            }
        });
    }

    /**
     * Scope a query to only include active job listings.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('deadline')
                    ->orWhere('deadline', '>=', now());
            });
    }

    /**
     * Scope a query to only include featured job listings.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to filter by category.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $category
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to filter by department.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $department
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByDepartment($query, string $department)
    {
        return $query->where('department', $department);
    }

    /**
     * Scope a query to filter by location.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $location
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByLocation($query, string $location)
    {
        return $query->where('location', $location);
    }

    /**
     * Scope a query to filter by job type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('job_type', $type);
    }

    /**
     * Scope a query to search by title or summary.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('summary', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Check if the job listing has expired.
     */
    public function isExpired(): bool
    {
        return $this->deadline && $this->deadline->isPast();
    }

    /**
     * Get the job type label.
     */
    public function getJobTypeLabelAttribute(): string
    {
        return \App\Enums\JobType::tryFrom($this->job_type)?->label() ?? $this->job_type;
    }

    /**
     * Get the location label.
     */
    public function getLocationLabelAttribute(): string
    {
        return \App\Enums\JobLocation::tryFrom($this->location)?->label() ?? $this->location;
    }

    /**
     * Get the category label.
     */
    public function getCategoryLabelAttribute(): string
    {
        return \App\Enums\JobCategory::tryFrom($this->category)?->label() ?? $this->category;
    }
}
