<?php

namespace App\Filament\Resources;

use App\Enums\ContactSubmissionStatus;
use App\Filament\Resources\ContactSubmissionResource\Pages;
use App\Models\ContactSubmission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactSubmissionResource extends Resource
{
    protected static ?string $model = ContactSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Inquiries';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'email';

    protected static int $globalSearchResultsLimit = 5;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'new')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['first_name', 'last_name', 'email', 'company'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Name' => $record->full_name,
            'Project' => $record->project_type,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Contact Information')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->disabled()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('last_name')
                            ->disabled()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('email')
                            ->disabled()
                            ->email(),
                        Forms\Components\TextInput::make('phone')
                            ->disabled(),
                        Forms\Components\TextInput::make('company')
                            ->disabled(),
                    ])->columns(2),

                Forms\Components\Section::make('Project Details')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->schema([
                        Forms\Components\Select::make('project_type')
                            ->disabled()
                            ->options(ContactSubmission::getProjectTypeOptions()),
                        Forms\Components\Select::make('timeline')
                            ->disabled()
                            ->options(ContactSubmission::getTimelineOptions()),
                        Forms\Components\Select::make('budget_range')
                            ->disabled()
                            ->options(ContactSubmission::getBudgetRangeOptions()),
                        Forms\Components\Textarea::make('size_requirements')
                            ->disabled()
                            ->rows(2)
                            ->columnSpanFull(),
                    ])->columns(3),

                Forms\Components\Section::make('Additional Information')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->schema([
                        Forms\Components\Textarea::make('message')
                            ->disabled()
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Submission Details')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->disabled()
                            ->options(ContactSubmissionStatus::options()),
                        Forms\Components\DateTimePicker::make('created_at')
                            ->disabled()
                            ->label('Submitted At'),
                        Forms\Components\DateTimePicker::make('updated_at')
                            ->disabled()
                            ->label('Last Updated'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Name')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(['first_name'])
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->copyableState(fn (string $state): string => $state)
                    ->icon('heroicon-o-envelope'),
                Tables\Columns\TextColumn::make('company')
                    ->searchable()
                    ->toggleable()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('project_type')
                    ->badge()
                    ->color('primary')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('timeline')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Immediate' => 'danger',
                        '1-3 Months' => 'warning',
                        '3-6 Months' => 'info',
                        default => 'gray',
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('budget_range')
                    ->badge()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->tooltip(fn ($record) => $record->created_at->format('M d, Y h:i A')),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(ContactSubmissionStatus::options()),
                Tables\Filters\SelectFilter::make('project_type')
                    ->options(ContactSubmission::getProjectTypeOptions()),
                Tables\Filters\SelectFilter::make('timeline')
                    ->options(ContactSubmission::getTimelineOptions()),
                Tables\Filters\SelectFilter::make('budget_range')
                    ->options(ContactSubmission::getBudgetRangeOptions()),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\BulkAction::make('markAsContacted')
                        ->label('Mark as Contacted')
                        ->icon('heroicon-o-phone')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Mark as Contacted')
                        ->modalDescription('Are you sure you want to mark the selected submissions as contacted?')
                        ->action(function (Collection $records): void {
                            $records->each(fn ($record) => $record->markAsContacted());
                        })
                        ->deselectRecordsAfterCompletion()
                        ->successNotificationTitle('Submissions marked as contacted'),
                    Tables\Actions\BulkAction::make('markAsConverted')
                        ->label('Mark as Converted')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Mark as Converted')
                        ->modalDescription('Are you sure you want to mark the selected submissions as converted?')
                        ->action(function (Collection $records): void {
                            $records->each(fn ($record) => $record->markAsConverted());
                        })
                        ->deselectRecordsAfterCompletion()
                        ->successNotificationTitle('Submissions marked as converted'),
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
            'index' => Pages\ListContactSubmissions::route('/'),
            'view' => Pages\ViewContactSubmission::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
