<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ProductEnvironment: string implements HasLabel
{
    case OUTDOOR = 'Outdoor';
    case INDOOR = 'Indoor';
    case INDOOR_OUTDOOR = 'Indoor/Outdoor';

    public function getLabel(): ?string
    {
        return $this->value;
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $case) => [
            $case->value => $case->getLabel(),
        ])->toArray();
    }
}
