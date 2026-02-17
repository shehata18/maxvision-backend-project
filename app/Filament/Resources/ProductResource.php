<?php

namespace App\Filament\Resources;

use App\Enums\ProductCategory;
use App\Enums\ProductEnvironment;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Products';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    protected static int $globalSearchResultsLimit = 5;

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'series', 'tagline'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Series' => $record->series,
            'Category' => $record->category,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                if (! $get('slug') || $get('slug') === Str::slug($get('name'))) {
                                    $set('slug', Str::slug($state));
                                }
                            }),
                        Forms\Components\TextInput::make('series')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('PTF Series'),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Auto-generated from product name'),
                        Forms\Components\Select::make('category')
                            ->required()
                            ->options(ProductCategory::options()),
                        Forms\Components\Select::make('environment')
                            ->required()
                            ->options(ProductEnvironment::options()),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->inline(false),
                    ])->columns(2),

                Forms\Components\Section::make('Pricing & Marketing')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->nullable()
                            ->maxLength(100)
                            ->placeholder('Contact for Quote'),
                        Forms\Components\TextInput::make('tagline')
                            ->nullable()
                            ->maxLength(255)
                            ->placeholder('High-brightness outdoor LED...')
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('description')
                            ->nullable()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'bulletList',
                                'orderedList',
                                'link',
                            ])
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Technical Specifications')
                    ->schema([
                        Forms\Components\TextInput::make('pixel_pitch')
                            ->required()
                            ->numeric()
                            ->step(0.1)
                            ->suffix('mm')
                            ->placeholder('3.0'),
                        Forms\Components\TextInput::make('brightness_min')
                            ->required()
                            ->numeric()
                            ->suffix('nits')
                            ->placeholder('6000'),
                        Forms\Components\TextInput::make('brightness_max')
                            ->required()
                            ->numeric()
                            ->suffix('nits')
                            ->placeholder('7500'),
                        Forms\Components\TextInput::make('cabinet_size')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('960×960×90mm'),
                        Forms\Components\TextInput::make('weight')
                            ->required()
                            ->maxLength(50)
                            ->placeholder('28kg/cabinet'),
                        Forms\Components\TextInput::make('power_consumption')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Max 650W/m², Avg 220W/m²'),
                        Forms\Components\TextInput::make('protection_rating')
                            ->required()
                            ->maxLength(50)
                            ->placeholder('IP65 Front / IP54 Rear'),
                        Forms\Components\TextInput::make('lifespan')
                            ->required()
                            ->maxLength(50)
                            ->placeholder('100,000 hours'),
                        Forms\Components\TextInput::make('operating_temp')
                            ->required()
                            ->maxLength(50)
                            ->placeholder('-30°C to +55°C'),
                    ])->columns(2),

                Forms\Components\Section::make('Images')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->maxSize(5120)
                            ->disk('public')
                            ->directory('products')
                            ->imageEditor()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),
                        Forms\Components\FileUpload::make('gallery')
                            ->multiple()
                            ->image()
                            ->maxFiles(10)
                            ->maxSize(5120)
                            ->disk('public')
                            ->directory('products/gallery')
                            ->imageEditor()
                            ->reorderable()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),
                    ])->columns(2),

                Forms\Components\Section::make('Features')
                    ->schema([
                        Forms\Components\Repeater::make('features')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('icon')
                                    ->required()
                                    ->maxLength(50)
                                    ->placeholder('Sun, Shield, Zap, etc.')
                                    ->helperText('Lucide icon name'),
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('7000 nits Brightness'),
                                Forms\Components\Textarea::make('description')
                                    ->required()
                                    ->rows(2)
                                    ->placeholder('Crystal clear visibility...'),
                            ])
                            ->columns(1)
                            ->defaultItems(0)
                            ->addActionLabel('Add Feature')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'New Feature'),
                    ])->collapsible(),

                Forms\Components\Section::make('Applications')
                    ->schema([
                        Forms\Components\Repeater::make('applications')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Billboards'),
                                Forms\Components\Hidden::make('order')
                                    ->default(0),
                            ])
                            ->columns(1)
                            ->defaultItems(0)
                            ->addActionLabel('Add Application')
                            ->reorderable()
                            ->collapsible(),
                    ])->collapsible(),

                Forms\Components\Section::make('Additional Specifications')
                    ->schema([
                        Forms\Components\Repeater::make('specifications')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('spec_key')
                                    ->required()
                                    ->maxLength(100)
                                    ->placeholder('refreshRate')
                                    ->label('Key'),
                                Forms\Components\TextInput::make('spec_value')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('3840Hz')
                                    ->label('Value'),
                                Forms\Components\Hidden::make('order')
                                    ->default(0),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Add Specification')
                            ->reorderable()
                            ->collapsible(),
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('series')
                    ->searchable()
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'outdoor' => 'success',
                        'indoor' => 'info',
                        'transparent' => 'warning',
                        'posters' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('pixel_pitch')
                    ->sortable()
                    ->suffix(' mm')
                    ->alignEnd(),
                Tables\Columns\TextColumn::make('brightness_range')
                    ->label('Brightness')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('price')
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options(ProductCategory::options()),
                Tables\Filters\SelectFilter::make('environment')
                    ->options(ProductEnvironment::options()),
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
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\FeaturesRelationManager::class,
            RelationManagers\ApplicationsRelationManager::class,
            RelationManagers\SpecificationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
