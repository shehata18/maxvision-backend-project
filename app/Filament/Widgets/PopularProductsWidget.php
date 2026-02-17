<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ProductResource;
use App\Services\AnalyticsService;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PopularProductsWidget extends BaseWidget
{
    protected static ?string $heading = 'Most Viewed Products';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $analytics = app(AnalyticsService::class);

        return $table
            ->query(
                \App\Models\Product::active()->orderByDesc('view_count')->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->weight('bold')
                    ->searchable(),
                Tables\Columns\TextColumn::make('series')
                    ->badge(),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'outdoor' => 'success',
                        'indoor' => 'info',
                        'transparent' => 'warning',
                        'posters' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('view_count')
                    ->label('Views')
                    ->sortable()
                    ->numeric(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Added')
                    ->date(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn (\App\Models\Product $record): string => ProductResource::getUrl('view', ['record' => $record]))
                    ->icon('heroicon-o-eye'),
            ])
            ->paginated(false);
    }
}
