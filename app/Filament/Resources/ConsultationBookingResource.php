<?php

namespace App\Filament\Resources;

use App\Enums\ConsultationBookingStatus;
use App\Filament\Resources\ConsultationBookingResource\Pages;
use App\Models\ConsultationBooking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConsultationBookingResource extends Resource
{
    protected static ?string $model = ConsultationBooking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    
    protected static ?string $navigationLabel = 'Consultation Bookings';
    
    protected static ?string $modelLabel = 'Consultation Booking';
    
    protected static ?string $pluralModelLabel = 'Consultation Bookings';
    
    protected static ?string $navigationGroup = 'Customer Relations';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Customer Information')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->label('First Name')
                            ->required()
                            ->maxLength(255)
                            ->disabled(fn ($context) => $context === 'edit'),
                        Forms\Components\TextInput::make('last_name')
                            ->label('Last Name')
                            ->required()
                            ->maxLength(255)
                            ->disabled(fn ($context) => $context === 'edit'),
                        Forms\Components\TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->disabled(fn ($context) => $context === 'edit'),
                        Forms\Components\TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->maxLength(255)
                            ->disabled(fn ($context) => $context === 'edit'),
                        Forms\Components\TextInput::make('company')
                            ->label('Company Name')
                            ->maxLength(255)
                            ->disabled(fn ($context) => $context === 'edit'),
                    ])
                    ->columns(2)
                    ->description(fn ($context) => $context === 'edit' ? 'Customer information cannot be modified' : null),
                    
                Forms\Components\Section::make('Appointment Details')
                    ->schema([
                        Forms\Components\DatePicker::make('preferred_date')
                            ->label('Preferred Date')
                            ->required()
                            ->native(false)
                            ->displayFormat('F j, Y')
                            ->minDate(now())
                            ->disabled(fn ($context) => $context === 'edit'),
                        Forms\Components\Select::make('preferred_time')
                            ->label('Preferred Time')
                            ->required()
                            ->options(ConsultationBooking::getTimeSlots())
                            ->disabled(fn ($context) => $context === 'edit'),
                        Forms\Components\Textarea::make('message')
                            ->label('Customer Message')
                            ->rows(3)
                            ->columnSpanFull()
                            ->disabled(fn ($context) => $context === 'edit'),
                    ])
                    ->columns(2)
                    ->description(fn ($context) => $context === 'edit' ? 'Appointment details cannot be modified. Contact the customer to reschedule.' : null),
                    
                Forms\Components\Section::make('Status & Notes')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->required()
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('pending')
                            ->native(false)
                            ->helperText('Update the consultation status'),
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Admin Notes')
                            ->rows(4)
                            ->columnSpanFull()
                            ->placeholder('Add internal notes about this consultation...')
                            ->helperText('Internal notes only visible to admin team. Use this to track: follow-up actions, customer requirements discussed, products recommended, or any special considerations.')
                            ->hint('Examples: "Customer interested in outdoor displays for retail", "Follow up on pricing for 10x15ft screen", "Discussed installation timeline"'),
                    ])
                    ->description('Only status and notes can be modified'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Customer Name')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(['first_name', 'last_name'])
                    ->weight('medium'),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-envelope'),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->toggleable()
                    ->icon('heroicon-m-phone'),
                Tables\Columns\TextColumn::make('company')
                    ->label('Company')
                    ->searchable()
                    ->toggleable()
                    ->icon('heroicon-m-building-office-2'),
                Tables\Columns\TextColumn::make('preferred_date')
                    ->label('Date')
                    ->date('M j, Y')
                    ->sortable()
                    ->icon('heroicon-m-calendar'),
                Tables\Columns\TextColumn::make('preferred_time')
                    ->label('Time')
                    ->icon('heroicon-m-clock'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        ConsultationBookingStatus::PENDING, 'pending' => 'warning',
                        ConsultationBookingStatus::CONFIRMED, 'confirmed' => 'success',
                        ConsultationBookingStatus::COMPLETED, 'completed' => 'info',
                        ConsultationBookingStatus::CANCELLED, 'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state): string => is_string($state) ? ucfirst($state) : $state->label()),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->multiple(),
                Tables\Filters\Filter::make('preferred_date')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('From Date'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Until Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('preferred_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('preferred_date', '<=', $date),
                            );
                    }),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('confirm')
                    ->label('Confirm')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn (ConsultationBooking $record) => $record->markAsConfirmed())
                    ->visible(fn (ConsultationBooking $record) => $record->status === ConsultationBookingStatus::PENDING),
                Tables\Actions\Action::make('complete')
                    ->label('Complete')
                    ->icon('heroicon-o-check-badge')
                    ->color('info')
                    ->requiresConfirmation()
                    ->action(fn (ConsultationBooking $record) => $record->markAsCompleted())
                    ->visible(fn (ConsultationBooking $record) => $record->status === ConsultationBookingStatus::CONFIRMED),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Customer Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('full_name')
                            ->label('Full Name'),
                        Infolists\Components\TextEntry::make('email')
                            ->label('Email')
                            ->copyable()
                            ->icon('heroicon-m-envelope'),
                        Infolists\Components\TextEntry::make('phone')
                            ->label('Phone')
                            ->icon('heroicon-m-phone'),
                        Infolists\Components\TextEntry::make('company')
                            ->label('Company')
                            ->icon('heroicon-m-building-office-2'),
                    ])
                    ->columns(2),
                    
                Infolists\Components\Section::make('Appointment Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('preferred_date')
                            ->label('Preferred Date')
                            ->date('l, F j, Y')
                            ->icon('heroicon-m-calendar'),
                        Infolists\Components\TextEntry::make('preferred_time')
                            ->label('Preferred Time')
                            ->icon('heroicon-m-clock'),
                        Infolists\Components\TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn ($state): string => match ($state) {
                                ConsultationBookingStatus::PENDING, 'pending' => 'warning',
                                ConsultationBookingStatus::CONFIRMED, 'confirmed' => 'success',
                                ConsultationBookingStatus::COMPLETED, 'completed' => 'info',
                                ConsultationBookingStatus::CANCELLED, 'cancelled' => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn ($state): string => is_string($state) ? ucfirst($state) : $state->label()),
                        Infolists\Components\TextEntry::make('message')
                            ->label('Customer Message')
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
                    
                Infolists\Components\Section::make('Admin Notes')
                    ->schema([
                        Infolists\Components\TextEntry::make('admin_notes')
                            ->label('Notes')
                            ->placeholder('No notes added yet'),
                    ])
                    ->collapsible(),
                    
                Infolists\Components\Section::make('Metadata')
                    ->schema([
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Submitted At')
                            ->dateTime('F j, Y g:i A'),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime('F j, Y g:i A'),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConsultationBookings::route('/'),
            // Removed create page - consultations come from website only
            'view' => Pages\ViewConsultationBooking::route('/{record}'),
            'edit' => Pages\EditConsultationBooking::route('/{record}/edit'),
        ];
    }
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
