<?php

namespace App\Filament\Resources\ContactSubmissionResource\Pages;

use App\Enums\ContactSubmissionStatus;
use App\Filament\Resources\ContactSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewContactSubmission extends ViewRecord
{
    protected static string $resource = ContactSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('markAsContacted')
                ->label('Mark as Contacted')
                ->icon('heroicon-o-phone')
                ->color('warning')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->status === ContactSubmissionStatus::NEW)
                ->action(function () {
                    $this->record->markAsContacted();
                    $this->refreshFormData(['status']);
                }),
            Actions\Action::make('markAsConverted')
                ->label('Mark as Converted')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->status === ContactSubmissionStatus::CONTACTED)
                ->action(function () {
                    $this->record->markAsConverted();
                    $this->refreshFormData(['status']);
                }),
            Actions\DeleteAction::make(),
        ];
    }
}
