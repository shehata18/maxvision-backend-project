<?php

namespace App\Filament\Widgets;

use App\Enums\ConsultationBookingStatus;
use App\Models\ConsultationBooking;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UpcomingConsultationsWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Upcoming Consultations')
            ->query(
                ConsultationBooking::query()
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->where('preferred_date', '>=', now()->toDateString())
                    ->orderBy('preferred_date')
                    ->orderBy('preferred_time')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('preferred_date')
                    ->label('Date')
                    ->date('M j, Y')
                    ->sortable()
                    ->icon('heroicon-m-calendar'),
                Tables\Columns\TextColumn::make('preferred_time')
                    ->label('Time')
                    ->icon('heroicon-m-clock'),
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Customer')
                    ->searchable(['first_name', 'last_name'])
                    ->weight('medium'),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->icon('heroicon-m-envelope')
                    ->copyable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->icon('heroicon-m-phone'),
                Tables\Columns\TextColumn::make('company')
                    ->label('Company')
                    ->icon('heroicon-m-building-office-2')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        ConsultationBookingStatus::PENDING, 'pending' => 'warning',
                        ConsultationBookingStatus::CONFIRMED, 'confirmed' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state): string => is_string($state) ? ucfirst($state) : $state->label()),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-m-eye')
                    ->url(fn (ConsultationBooking $record): string => route('filament.admin.resources.consultation-bookings.view', ['record' => $record])),
            ]);
    }
}
