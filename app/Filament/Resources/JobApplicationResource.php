<?php

namespace App\Filament\Resources;

use App\Enums\JobApplicationStatus;
use App\Filament\Resources\JobApplicationResource\Pages;
use App\Models\JobApplication;
use App\Models\JobListing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class JobApplicationResource extends Resource
{
    protected static ?string $model = JobApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Company';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'full_name';

    protected static int $globalSearchResultsLimit = 5;

    public static function getGloballySearchableAttributes(): array
    {
        return ['first_name', 'last_name', 'email', 'jobListing.title'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Position' => $record->jobListing?->title ?? 'General Application',
            'Status' => $record->status_label,
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', JobApplicationStatus::Pending->value)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Applicant Information')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->required()
                            ->maxLength(100)
                            ->disabled(),
                        Forms\Components\TextInput::make('last_name')
                            ->required()
                            ->maxLength(100)
                            ->disabled(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->disabled(),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(50)
                            ->disabled(),
                    ])->columns(2),

                Forms\Components\Section::make('Application Details')
                    ->schema([
                        Forms\Components\Select::make('job_listing_id')
                            ->label('Position Applied For')
                            ->relationship('jobListing', 'title')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\Toggle::make('is_general_application')
                            ->label('General Application')
                            ->disabled(),
                        Forms\Components\Textarea::make('cover_letter')
                            ->rows(4)
                            ->disabled()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('linkedin_url')
                            ->url()
                            ->disabled(),
                        Forms\Components\TextInput::make('portfolio_url')
                            ->url()
                            ->disabled(),
                    ])->columns(2),

                Forms\Components\Section::make('Resume')
                    ->schema([
                        Forms\Components\Placeholder::make('resume_download')
                            ->label('Uploaded Resume')
                            ->content(fn ($record) => $record->resume_original_name ?? 'No resume uploaded')
                            ->visible(fn ($record) => $record?->resume_path),
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('download_resume')
                                ->label('Download Resume')
                                ->icon('heroicon-o-arrow-down-tray')
                                ->url(fn ($record) => route('admin.job-applications.download-resume', $record))
                                ->openUrlInNewTab()
                                ->visible(fn ($record) => $record?->resume_path),
                        ]),
                    ]),

                Forms\Components\Section::make('Status & Notes')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->required()
                            ->options(JobApplicationStatus::options())
                            ->live()
                            ->afterStateUpdated(function ($state, $record) {
                                if ($record && in_array($state, JobApplicationStatus::activeStatuses()) && !$record->reviewed_at) {
                                    $record->update([
                                        'reviewed_at' => now(),
                                        'reviewed_by' => auth()->id(),
                                    ]);
                                }
                            }),
                        Forms\Components\Textarea::make('notes')
                            ->label('Internal Notes')
                            ->rows(3)
                            ->placeholder('Add notes about this application...')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Applicant')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-envelope'),
                Tables\Columns\TextColumn::make('jobListing.title')
                    ->label('Position')
                    ->searchable()
                    ->placeholder('General Application')
                    ->limit(30),
                Tables\Columns\IconColumn::make('is_general_application')
                    ->label('General')
                    ->boolean()
                    ->trueIcon('heroicon-o-document')
                    ->falseIcon('heroicon-o-briefcase'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => JobApplicationStatus::tryFrom($state)?->color() ?? 'gray')
                    ->formatStateUsing(fn (string $state): string => JobApplicationStatus::tryFrom($state)?->label() ?? $state),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Applied')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reviewed_at')
                    ->label('Reviewed')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(JobApplicationStatus::options()),
                Tables\Filters\SelectFilter::make('job_listing_id')
                    ->label('Position')
                    ->relationship('jobListing', 'title'),
                Tables\Filters\TernaryFilter::make('is_general_application')
                    ->label('Application Type')
                    ->placeholder('All')
                    ->trueLabel('General Applications')
                    ->falseLabel('Job-Specific'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('download_resume')
                    ->label('Resume')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn ($record) => route('admin.job-applications.download-resume', $record))
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => $record->resume_path),
                Tables\Actions\Action::make('email')
                    ->icon('heroicon-o-envelope')
                    ->url(fn ($record) => "mailto:{$record->email}?subject=Re: Your Application to Maxvision Display")
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('update_status')
                        ->label('Update Status')
                        ->icon('heroicon-o-check-circle')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->label('New Status')
                                ->options(JobApplicationStatus::options())
                                ->required(),
                        ])
                        ->action(function (array $data, $records) {
                            foreach ($records as $record) {
                                $record->update([
                                    'status' => $data['status'],
                                    'reviewed_at' => now(),
                                    'reviewed_by' => auth()->id(),
                                ]);
                            }
                            Notification::make()
                                ->title('Status Updated')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobApplications::route('/'),
            'view' => Pages\ViewJobApplication::route('/{record}'),
            'edit' => Pages\EditJobApplication::route('/{record}/edit'),
        ];
    }
}
