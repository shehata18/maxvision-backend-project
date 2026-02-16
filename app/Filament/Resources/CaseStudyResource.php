<?php

namespace App\Filament\Resources;

use App\Enums\CaseStudyIndustry;
use App\Filament\Resources\CaseStudyResource\Pages;
use App\Models\CaseStudy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class CaseStudyResource extends Resource
{
    protected static ?string $model = CaseStudy::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Case Studies';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    protected static int $globalSearchResultsLimit = 5;

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'client', 'industry', 'location'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Client' => $record->client,
            'Industry' => $record->industry,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                if (! $get('slug') || $get('slug') === Str::slug($get('title'))) {
                                    $set('slug', Str::slug($state));
                                }
                            }),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Auto-generated from title'),
                        Forms\Components\TextInput::make('client')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Metro Fashion Group'),
                        Forms\Components\Select::make('industry')
                            ->required()
                            ->options(CaseStudyIndustry::options()),
                        Forms\Components\TextInput::make('location')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Toronto, ON'),
                        Forms\Components\TextInput::make('date')
                            ->required()
                            ->maxLength(50)
                            ->placeholder('2025'),
                        Forms\Components\Toggle::make('is_featured')
                            ->default(false)
                            ->inline(false),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->inline(false),
                    ])->columns(2),

                Forms\Components\Section::make('Content')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->maxSize(5120)
                            ->disk('public')
                            ->directory('case-studies')
                            ->imageEditor(),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->rows(3)
                            ->placeholder('Brief overview of the project...')
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('challenge')
                            ->nullable()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'bulletList',
                                'orderedList',
                                'link',
                            ])
                            ->label('Challenge')
                            ->placeholder('Describe the client challenge...'),
                        Forms\Components\RichEditor::make('solution')
                            ->nullable()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'bulletList',
                                'orderedList',
                                'link',
                            ])
                            ->label('Solution')
                            ->placeholder('Describe the MaxVision solution...'),
                    ])->columns(2),

                Forms\Components\Section::make('Metrics')
                    ->schema([
                        Forms\Components\Repeater::make('metrics')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('label')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Foot Traffic Increase'),
                                Forms\Components\TextInput::make('value')
                                    ->required()
                                    ->maxLength(100)
                                    ->placeholder('+34%'),
                                Forms\Components\TextInput::make('icon')
                                    ->required()
                                    ->maxLength(50)
                                    ->placeholder('TrendingUp')
                                    ->helperText('Lucide icon name (TrendingUp, DollarSign, Eye, BarChart3, etc.)'),
                                Forms\Components\Hidden::make('order')
                                    ->default(0),
                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->addActionLabel('Add Metric')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? 'New Metric'),
                    ])->collapsible(),

                Forms\Components\Section::make('Technical Specifications')
                    ->schema([
                        Forms\Components\Repeater::make('specs')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('label')
                                    ->required()
                                    ->maxLength(100)
                                    ->placeholder('Display Size'),
                                Forms\Components\TextInput::make('value')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('42 m²'),
                                Forms\Components\Hidden::make('order')
                                    ->default(0),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Add Specification')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? 'New Specification'),
                    ])->collapsible(),

                Forms\Components\Section::make('Products Used')
                    ->schema([
                        Forms\Components\Select::make('products')
                            ->relationship('products', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->helperText('Select products used in this case study. Product display names are managed via seeders.'),
                    ])->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->circular()
                    ->size(50)
                    ->disk('public'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('client')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('industry')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'retail' => 'success',
                        'outdoor' => 'warning',
                        'corporate' => 'info',
                        'events' => 'primary',
                        'architecture' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('location')
                    ->icon('heroicon-o-map-pin')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('date')
                    ->icon('heroicon-o-calendar')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray')
                    ->label('Featured'),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('industry')
                    ->options(CaseStudyIndustry::options()),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured')
                    ->placeholder('All')
                    ->trueLabel('Featured')
                    ->falseLabel('Not Featured'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All')
                    ->trueLabel('Active')
                    ->falseLabel('Inactive'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('toggleFeatured')
                        ->label('Toggle Featured')
                        ->icon('heroicon-o-star')
                        ->action(function (Collection $records): void {
                            foreach ($records as $record) {
                                $record->update([
                                    'is_featured' => ! $record->is_featured,
                                ]);
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
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
            'index' => Pages\ListCaseStudies::route('/'),
            'create' => Pages\CreateCaseStudy::route('/create'),
            'view' => Pages\ViewCaseStudy::route('/{record}'),
            'edit' => Pages\EditCaseStudy::route('/{record}/edit'),
        ];
    }
}
