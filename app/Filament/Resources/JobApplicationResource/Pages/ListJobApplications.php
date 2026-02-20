<?php

namespace App\Filament\Resources\JobApplicationResource\Pages;

use App\Filament\Resources\JobApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJobApplications extends ListRecords
{
    protected static string $resource = JobApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('export')
                ->label('Export CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    $applications = \App\Models\JobApplication::with('jobListing')->get();
                    $csv = \Illuminate\Support\Facades\Response::streamDownload(function () use ($applications) {
                        echo "Name,Email,Phone,Position,Status,Applied At\n";
                        foreach ($applications as $app) {
                            echo sprintf(
                                '"%s","%s","%s","%s","%s","%s"' . "\n",
                                $app->full_name,
                                $app->email,
                                $app->phone ?? '',
                                $app->jobListing?->title ?? 'General Application',
                                $app->status_label,
                                $app->created_at->format('Y-m-d H:i')
                            );
                        }
                    }, 'job-applications-' . date('Y-m-d') . '.csv');
                    return $csv;
                }),
        ];
    }
}
