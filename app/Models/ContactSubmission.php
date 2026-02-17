<?php

namespace App\Models;

use App\Enums\ContactSubmissionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

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
 * @property ContactSubmissionStatus $status
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
    use HasFactory, SoftDeletes, Notifiable;

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
        'status' => ContactSubmissionStatus::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope a query to filter by status.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to filter new submissions.
     */
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    /**
     * Scope a query to filter contacted submissions.
     */
    public function scopeContacted($query)
    {
        return $query->where('status', 'contacted');
    }

    /**
     * Scope a query to filter converted submissions.
     */
    public function scopeConverted($query)
    {
        return $query->where('status', 'converted');
    }

    /**
     * Scope a query to filter by project type.
     */
    public function scopeByProjectType($query, string $type)
    {
        return $query->where('project_type', $type);
    }

    /**
     * Scope a query to filter by timeline.
     */
    public function scopeByTimeline($query, string $timeline)
    {
        return $query->where('timeline', $timeline);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    /**
     * Scope a query to order by most recent first.
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Route notifications for the mail channel.
     */
    public function routeNotificationForMail(): string
    {
        return $this->email;
    }

    /**
     * Get the full name of the contact.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Mark this submission as contacted.
     */
    public function markAsContacted(): self
    {
        $this->update(['status' => ContactSubmissionStatus::CONTACTED]);
        return $this;
    }

    /**
     * Mark this submission as converted.
     */
    public function markAsConverted(): self
    {
        $this->update(['status' => ContactSubmissionStatus::CONVERTED]);
        return $this;
    }

    /**
     * Get project type options matching the frontend form.
     */
    public static function getProjectTypeOptions(): array
    {
        return [
            'Indoor LED Display' => 'Indoor LED Display',
            'Outdoor LED Display' => 'Outdoor LED Display',
            'Transparent LED' => 'Transparent LED',
            'LED Video Wall' => 'LED Video Wall',
            'Rental LED Screen' => 'Rental LED Screen',
            'Custom Solution' => 'Custom Solution',
            'Other' => 'Other',
        ];
    }

    /**
     * Get timeline options matching the frontend form.
     */
    public static function getTimelineOptions(): array
    {
        return [
            'Immediate' => 'Immediate',
            '1-3 Months' => '1-3 Months',
            '3-6 Months' => '3-6 Months',
            '6-12 Months' => '6-12 Months',
            '12+ Months' => '12+ Months',
        ];
    }

    /**
     * Get budget range options matching the frontend form.
     */
    public static function getBudgetRangeOptions(): array
    {
        return [
            'Under $10,000' => 'Under $10,000',
            '$10,000 - $25,000' => '$10,000 - $25,000',
            '$25,000 - $50,000' => '$25,000 - $50,000',
            '$50,000 - $100,000' => '$50,000 - $100,000',
            'Over $100,000' => 'Over $100,000',
        ];
    }
}
