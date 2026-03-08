<?php

namespace App\Filament\Resources\JobListingResource\Pages;

use App\Filament\Resources\JobListingResource;
use App\Filament\Widgets\JobListingStatsWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJobListings extends ListRecords
{
    protected static string $resource = JobListingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            JobListingStatsWidget::class,
        ];
    }
}
