<?php

namespace App\Filament\Resources\ConsultationBookingResource\Widgets;

use App\Models\ConsultationBooking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ConsultationBookingsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Total bookings
        $totalBookings = ConsultationBooking::count();
        
        // Status counts
        $pendingCount = ConsultationBooking::where('status', 'pending')->count();
        $confirmedCount = ConsultationBooking::where('status', 'confirmed')->count();
        $completedCount = ConsultationBooking::where('status', 'completed')->count();
        $cancelledCount = ConsultationBooking::where('status', 'cancelled')->count();
        
        // This month stats
        $thisMonthBookings = ConsultationBooking::where('created_at', '>=', now()->startOfMonth())->count();
        $lastMonthBookings = ConsultationBooking::whereBetween('created_at', [
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth(),
        ])->count();
        
        $monthlyChange = $lastMonthBookings > 0 
            ? round((($thisMonthBookings - $lastMonthBookings) / $lastMonthBookings) * 100, 1)
            : 0;
            
        // Upcoming consultations (next 7 days)
        $upcomingCount = ConsultationBooking::whereIn('status', ['pending', 'confirmed'])
            ->whereBetween('preferred_date', [now()->toDateString(), now()->addDays(7)->toDateString()])
            ->count();
            
        // Conversion rate (completed / total)
        $conversionRate = $totalBookings > 0 
            ? round(($completedCount / $totalBookings) * 100, 1)
            : 0;

        return [
            Stat::make('Total Bookings', $totalBookings)
                ->description($thisMonthBookings . ' this month')
                ->descriptionIcon($monthlyChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($monthlyChange >= 0 ? 'success' : 'danger')
                ->chart($this->getMonthlyChart()),
                
            Stat::make('Pending', $pendingCount)
                ->description('Awaiting confirmation')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
                
            Stat::make('Confirmed', $confirmedCount)
                ->description('Scheduled appointments')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
                
            Stat::make('Upcoming (7 days)', $upcomingCount)
                ->description('Next week consultations')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),
                
            Stat::make('Completed', $completedCount)
                ->description($conversionRate . '% conversion rate')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
                
            Stat::make('Cancelled', $cancelledCount)
                ->description('Cancelled bookings')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }

    protected function getMonthlyChart(): array
    {
        $data = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = ConsultationBooking::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $data[] = $count;
        }
        
        return $data;
    }
}
