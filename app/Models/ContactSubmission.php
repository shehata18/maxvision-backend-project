<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * ContactSubmission Model
 *
 * Represents a contact form submission from the website.
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string|null $phone
 * @property string|null $company
 * @property string $project_type
 * @property string $timeline
 * @property string $size_requirements
 * @property string $budget_range
 * @property string|null $message
 * @property string $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @property-read string $full_name Concatenated first and last name
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ContactSubmission byStatus(string $status) Filter by status
 * @method static \Illuminate\Database\Eloquent\Builder|ContactSubmission byProjectType(string $type) Filter by project type
 * @method static \Illuminate\Database\Eloquent\Builder|ContactSubmission byTimeline(string $timeline) Filter by timeline
 * @method static \Illuminate\Database\Eloquent\Builder|ContactSubmission recent() Order by most recent first
 */
class ContactSubmission extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'company',
        'project_type',
        'timeline',
        'size_requirements',
        'budget_range',
        'message',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

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
     * Scope a query to filter by project type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByProjectType($query, string $type)
    {
        return $query->where('project_type', $type);
    }

    /**
     * Scope a query to filter by timeline.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $timeline
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByTimeline($query, string $timeline)
    {
        return $query->where('timeline', $timeline);
    }

    /**
     * Scope a query to order by most recent first.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Get the full name of the contact.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
