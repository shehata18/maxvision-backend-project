<?php

namespace App\Filament\Resources\ConsultationBookingResource\Widgets;

use Filament\Widgets\ChartWidget;

class ConsultationBookingStatsWidget extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
