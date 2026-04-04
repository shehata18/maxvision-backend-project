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
            self::OUTDOOR => 'Outdoor LCD',
            self::INDOOR => 'Indoor LCD',
            self::TRANSPARENT => 'Transparent',
            self::POSTERS => 'LCD Posters',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $case) => [
            $case->value => $case->getLabel(),
        ])->toArray();
    }
}
