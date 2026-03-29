<?php

namespace App\Models;

use App\Enums\ConsultationBookingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class ConsultationBooking extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'company',
        'preferred_date',
        'preferred_time',
        'message',
        'status',
        'admin_notes',
    ];

    protected $attributes = [
        'status' => 'pending',
    ];

    protected $casts = [
        'status' => ConsultationBookingStatus::class,
        'preferred_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the full name of the contact.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Route notifications for the mail channel.
     */
    public function routeNotificationForMail(): string
    {
        return $this->email;
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to filter pending bookings.
     */
    public function scopePending($query)
    {
        return $query->where('status', ConsultationBookingStatus::PENDING);
    }

    /**
     * Scope a query to filter confirmed bookings.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', ConsultationBookingStatus::CONFIRMED);
    }

    /**
     * Scope a query to order by most recent first.
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, $from, $to)
    {
        return $query->whereBetween('preferred_date', [$from, $to]);
    }

    /**
     * Mark this booking as confirmed.
     */
    public function markAsConfirmed(): self
    {
        $this->update(['status' => ConsultationBookingStatus::CONFIRMED]);
        return $this;
    }

    /**
     * Mark this booking as completed.
     */
    public function markAsCompleted(): self
    {
        $this->update(['status' => ConsultationBookingStatus::COMPLETED]);
        return $this;
    }

    /**
     * Mark this booking as cancelled.
     */
    public function markAsCancelled(): self
    {
        $this->update(['status' => ConsultationBookingStatus::CANCELLED]);
        return $this;
    }

    /**
     * Get available time slots.
     */
    public static function getTimeSlots(): array
    {
        return [
            '09:00-10:00' => '9:00 AM - 10:00 AM',
            '10:00-11:00' => '10:00 AM - 11:00 AM',
            '11:00-12:00' => '11:00 AM - 12:00 PM',
            '12:00-13:00' => '12:00 PM - 1:00 PM',
            '13:00-14:00' => '1:00 PM - 2:00 PM',
            '14:00-15:00' => '2:00 PM - 3:00 PM',
            '15:00-16:00' => '3:00 PM - 4:00 PM',
            '16:00-17:00' => '4:00 PM - 5:00 PM',
        ];
    }
}

