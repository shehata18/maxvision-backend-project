<?php

namespace App\Filament\Resources\ContactSubmissionResource\Pages;

use App\Filament\Resources\ContactSubmissionResource;
use App\Filament\Widgets\ContactSubmissionStatsWidget;
use App\Models\ContactSubmission;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListContactSubmissions extends ListRecords
{
    protected static string $resource = ContactSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('export')
                ->label('Export CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function (): StreamedResponse {
                    $filename = 'contact-submissions-' . now()->format('Y-m-d') . '.csv';

                    return response()->streamDownload(function () {
                        $handle = fopen('php://output', 'w');

                        // CSV Header
                        fputcsv($handle, [
                            'ID', 'First Name', 'Last Name', 'Email', 'Phone',
                            'Company', 'Project Type', 'Timeline', 'Size Requirements',
                            'Budget Range', 'Message', 'Status', 'Submitted At', 'Updated At',
                        ]);

                        // CSV Rows
                        ContactSubmission::query()
                            ->orderBy('created_at', 'desc')
                            ->each(function ($submission) use ($handle) {
                                fputcsv($handle, [
                                    $submission->id,
                                    $submission->first_name,
                                    $submission->last_name,
                                    $submission->email,
                                    $submission->phone,
                                    $submission->company,
                                    $submission->project_type,
                                    $submission->timeline,
                                    $submission->size_requirements,
                                    $submission->budget_range,
                                    $submission->message,
                                    $submission->status->getLabel(),
                                    $submission->created_at->format('Y-m-d H:i:s'),
                                    $submission->updated_at->format('Y-m-d H:i:s'),
                                ]);
                            });

                        fclose($handle);
                    }, $filename, [
                        'Content-Type' => 'text/csv',
                    ]);
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ContactSubmissionStatsWidget::class,
        ];
    }
}
