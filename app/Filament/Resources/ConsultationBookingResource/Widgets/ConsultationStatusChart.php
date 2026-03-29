<?php

namespace App\Filament\Resources\ConsultationBookingResource\Widgets;

use App\Models\ConsultationBooking;
use Filament\Widgets\ChartWidget;

class ConsultationStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Bookings by Status';
    
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $pending = ConsultationBooking::where('status', 'pending')->count();
        $confirmed = ConsultationBooking::where('status', 'confirmed')->count();
        $completed = ConsultationBooking::where('status', 'completed')->count();
        $cancelled = ConsultationBooking::where('status', 'cancelled')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Bookings by Status',
                    'data' => [$pending, $confirmed, $completed, $cancelled],
                    'backgroundColor' => [
                        'rgba(251, 191, 36, 0.8)',  // warning - pending
                        'rgba(34, 197, 94, 0.8)',   // success - confirmed
                        'rgba(59, 130, 246, 0.8)',  // info - completed
                        'rgba(239, 68, 68, 0.8)',   // danger - cancelled
                    ],
                    'borderColor' => [
                        'rgb(251, 191, 36)',
                        'rgb(34, 197, 94)',
                        'rgb(59, 130, 246)',
                        'rgb(239, 68, 68)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => ['Pending', 'Confirmed', 'Completed', 'Cancelled'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
    
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
