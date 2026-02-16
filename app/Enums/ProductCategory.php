<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ProductCategory: string implements HasLabel
{
    case OUTDOOR = 'outdoor';
    case INDOOR = 'indoor';
    case TRANSPARENT = 'transparent';
    case POSTERS = 'posters';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::OUTDOOR => 'Outdoor LED',
            self::INDOOR => 'Indoor LED',
            self::TRANSPARENT => 'Transparent',
            self::POSTERS => 'LED Posters',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $case) => [
            $case->value => $case->getLabel(),
        ])->toArray();
    }
}
