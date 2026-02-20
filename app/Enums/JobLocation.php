<?php

namespace App\Enums;

enum JobLocation: string
{
    case Toronto = 'toronto';
    case Vancouver = 'vancouver';
    case Montreal = 'montreal';
    case Remote = 'remote';
    case Hybrid = 'hybrid';

    /**
     * Get all options as an array for select inputs.
     */
    public static function options(): array
    {
        return [
            self::Toronto->value => 'Toronto, ON',
            self::Vancouver->value => 'Vancouver, BC',
            self::Montreal->value => 'Montreal, QC',
            self::Remote->value => 'Remote',
            self::Hybrid->value => 'Hybrid',
        ];
    }

    /**
     * Get the label for this enum value.
     */
    public function label(): string
    {
        return match ($this) {
            self::Toronto => 'Toronto, ON',
            self::Vancouver => 'Vancouver, BC',
            self::Montreal => 'Montreal, QC',
            self::Remote => 'Remote',
            self::Hybrid => 'Hybrid',
        };
    }
}
