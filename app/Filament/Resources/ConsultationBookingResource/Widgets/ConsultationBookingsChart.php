<?php

namespace App\Filament\Resources\ConsultationBookingResource\Widgets;

use App\Models\ConsultationBooking;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ConsultationBookingsChart extends ChartWidget
{
    protected static ?string $heading = 'Consultation Bookings Over Time';
    
    protected static ?string $maxHeight = '300px';
    
    public ?string $filter = '30days';

    protected function getData(): array
    {
        $data = $this->getChartData();

        return [
            'datasets' => [
                [
                    'label' => 'Consultation Bookings',
                    'data' => $data['counts'],
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $data['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
    
    protected function getFilters(): ?array
    {
        return [
            '7days' => 'Last 7 days',
            '30days' => 'Last 30 days',
            '90days' => 'Last 90 days',
            '12months' => 'Last 12 months',
        ];
    }
    
    protected function getChartData(): array
    {
        $filter = $this->filter ?? '30days';
        
        return match ($filter) {
            '7days' => $this->getLast7Days(),
            '30days' => $this->getLast30Days(),
            '90days' => $this->getLast90Days(),
            '12months' => $this->getLast12Months(),
            default => $this->getLast30Days(),
        };
    }
    
    protected function getLast7Days(): array
    {
        $labels = [];
        $counts = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('M j');
            $counts[] = ConsultationBooking::whereDate('created_at', $date->toDateString())->count();
        }
        
        return ['labels' => $labels, 'counts' => $counts];
    }
    
    protected function getLast30Days(): array
    {
        $labels = [];
        $counts = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('M j');
            $counts[] = ConsultationBooking::whereDate('created_at', $date->toDateString())->count();
        }
        
        return ['labels' => $labels, 'counts' => $counts];
    }
    
    protected function getLast90Days(): array
    {
        $labels = [];
        $counts = [];
        
        for ($i = 12; $i >= 0; $i--) {
            $startOfWeek = now()->subWeeks($i)->startOfWeek();
            $endOfWeek = now()->subWeeks($i)->endOfWeek();
            
            $labels[] = $startOfWeek->format('M j');
            $counts[] = ConsultationBooking::whereBetween('created_at', [
                $startOfWeek->toDateString(),
                $endOfWeek->toDateString()
            ])->count();
        }
        
        return ['labels' => $labels, 'counts' => $counts];
    }
    
    protected function getLast12Months(): array
    {
        $labels = [];
        $counts = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M Y');
            $counts[] = ConsultationBooking::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }
        
        return ['labels' => $labels, 'counts' => $counts];
    }
}
