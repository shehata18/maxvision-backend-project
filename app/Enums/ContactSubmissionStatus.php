<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ContactSubmissionStatus: string implements HasLabel, HasColor
{
    case NEW = 'new';
    case CONTACTED = 'contacted';
    case CONVERTED = 'converted';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::NEW => 'New Inquiry',
            self::CONTACTED => 'Contacted',
            self::CONVERTED => 'Converted to Client',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::NEW => 'info',
            self::CONTACTED => 'warning',
            self::CONVERTED => 'success',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $case) => [
            $case->value => $case->getLabel(),
        ])->toArray();
    }

    public static function badges(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $case) => [
            $case->value => $case->getColor(),
        ])->toArray();
    }
}
