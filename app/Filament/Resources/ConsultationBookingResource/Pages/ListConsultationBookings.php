<?php

namespace App\Filament\Resources\ConsultationBookingResource\Pages;

use App\Filament\Resources\ConsultationBookingResource;
use App\Filament\Resources\ConsultationBookingResource\Widgets\ConsultationBookingsChart;
use App\Filament\Resources\ConsultationBookingResource\Widgets\ConsultationBookingsOverview;
use App\Filament\Resources\ConsultationBookingResource\Widgets\ConsultationStatusChart;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConsultationBookings extends ListRecords
{
    protected static string $resource = ConsultationBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Removed create action - consultations should only come from website
        ];
    }
    
    protected function getHeaderWidgets(): array
    {
        return [
            ConsultationBookingsOverview::class,
        ];
    }
    
    protected function getFooterWidgets(): array
    {
        return [
            ConsultationBookingsChart::class,
            ConsultationStatusChart::class,
        ];
    }
}
