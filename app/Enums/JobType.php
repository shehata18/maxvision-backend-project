<?php

namespace App\Enums;

enum JobType: string
{
    case FullTime = 'full-time';
    case PartTime = 'part-time';
    case Contract = 'contract';
    case Internship = 'internship';
    case Remote = 'remote';

    /**
     * Get all options as an array for select inputs.
     */
    public static function options(): array
    {
        return [
            self::FullTime->value => 'Full-Time',
            self::PartTime->value => 'Part-Time',
            self::Contract->value => 'Contract',
            self::Internship->value => 'Internship',
            self::Remote->value => 'Remote',
        ];
    }

    /**
     * Get the label for this enum value.
     */
    public function label(): string
    {
        return match ($this) {
            self::FullTime => 'Full-Time',
            self::PartTime => 'Part-Time',
            self::Contract => 'Contract',
            self::Internship => 'Internship',
            self::Remote => 'Remote',
        };
    }
}
