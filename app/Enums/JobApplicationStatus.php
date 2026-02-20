<?php

namespace App\Enums;

enum JobApplicationStatus: string
{
    case Pending = 'pending';
    case Reviewing = 'reviewing';
    case Shortlisted = 'shortlisted';
    case Interviewed = 'interviewed';
    case Offered = 'offered';
    case Hired = 'hired';
    case Rejected = 'rejected';
    case Withdrawn = 'withdrawn';

    /**
     * Get all options as an array for select inputs.
     */
    public static function options(): array
    {
        return [
            self::Pending->value => 'Pending',
            self::Reviewing->value => 'Reviewing',
            self::Shortlisted->value => 'Shortlisted',
            self::Interviewed->value => 'Interviewed',
            self::Offered->value => 'Offered',
            self::Hired->value => 'Hired',
            self::Rejected->value => 'Rejected',
            self::Withdrawn->value => 'Withdrawn',
        ];
    }

    /**
     * Get the label for this enum value.
     */
    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Reviewing => 'Reviewing',
            self::Shortlisted => 'Shortlisted',
            self::Interviewed => 'Interviewed',
            self::Offered => 'Offered',
            self::Hired => 'Hired',
            self::Rejected => 'Rejected',
            self::Withdrawn => 'Withdrawn',
        };
    }

    /**
     * Get the color for this status.
     */
    public function color(): string
    {
        return match ($this) {
            self::Pending => 'gray',
            self::Reviewing => 'info',
            self::Shortlisted => 'primary',
            self::Interviewed => 'warning',
            self::Offered => 'success',
            self::Hired => 'success',
            self::Rejected => 'danger',
            self::Withdrawn => 'gray',
        };
    }

    /**
     * Get active statuses (application still in progress).
     */
    public static function activeStatuses(): array
    {
        return [
            self::Pending->value,
            self::Reviewing->value,
            self::Shortlisted->value,
            self::Interviewed->value,
            self::Offered->value,
        ];
    }

    /**
     * Get completed statuses (application process ended).
     */
    public static function completedStatuses(): array
    {
        return [
            self::Hired->value,
            self::Rejected->value,
            self::Withdrawn->value,
        ];
    }
}
