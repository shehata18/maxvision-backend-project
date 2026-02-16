<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CaseStudyIndustry: string implements HasLabel
{
    case RETAIL = 'retail';
    case OUTDOOR = 'outdoor';
    case CORPORATE = 'corporate';
    case EVENTS = 'events';
    case ARCHITECTURE = 'architecture';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::RETAIL => 'Retail & Commercial',
            self::OUTDOOR => 'Outdoor Advertising',
            self::CORPORATE => 'Corporate & Control Rooms',
            self::EVENTS => 'Events & Entertainment',
            self::ARCHITECTURE => 'Architectural',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $case) => [
            $case->value => $case->getLabel(),
        ])->toArray();
    }
}
