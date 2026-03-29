<?php

namespace App\Filament\Widgets;

use App\Models\ConsultationBooking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ConsultationBookingStatsWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        $totalBookings = ConsultationBooking::count();
        $pendingBookings = ConsultationBooking::where('status', 'pending')->count();
        $confirmedBookings = ConsultationBooking::where('status', 'confirmed')->count();
        $completedBookings = ConsultationBooking::where('status', 'completed')->count();
        
        // This week stats
        $thisWeekBookings = ConsultationBooking::where('created_at', '>=', now()->startOfWeek())->count();
        $lastWeekBookings = ConsultationBooking::whereBetween('created_at', [
            now()->subWeek()->startOfWeek(),
            now()->subWeek()->endOfWeek(),
        ])->count();
        
        $weeklyChange = $lastWeekBookings > 0 
            ? round((($thisWeekBookings - $lastWeekBookings) / $lastWeekBookings) * 100, 1)
            : 0;

        return [
            Stat::make('Total Consultation Bookings', $totalBookings)
                ->description($thisWeekBookings . ' this week')
                ->descriptionIcon($weeklyChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($weeklyChange >= 0 ? 'success' : 'danger')
                ->chart($this->getWeeklyChart()),
                
            Stat::make('Pending Bookings', $pendingBookings)
                ->description('Awaiting confirmation')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->url(route('filament.admin.resources.consultation-bookings.index', [
                    'tableFilters' => ['status' => ['values' => ['pending']]],
                ])),
                
            Stat::make('Confirmed Bookings', $confirmedBookings)
                ->description('Scheduled appointments')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
                
            Stat::make('Completed Bookings', $completedBookings)
                ->description('Finished consultations')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('info'),
        ];
    }

    protected function getWeeklyChart(): array
    {
        $data = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = ConsultationBooking::whereDate('created_at', $date)->count();
            $data[] = $count;
        }
        
        return $data;
    }
}
