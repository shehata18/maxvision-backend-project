<?php

namespace App\Filament\Resources;

use App\Enums\SolutionCategory;
use App\Filament\Resources\SolutionResource\Pages;
use App\Models\Solution;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class SolutionResource extends Resource
{
    protected static ?string $model = Solution::class;

    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';

    protected static ?string $navigationGroup = 'Solutions';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    protected static int $globalSearchResultsLimit = 5;

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'tagline', 'category'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Category' => $record->category,
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
                        Forms\Components\Select::make('category')
                            ->required()
                            ->options(SolutionCategory::options()),
                        Forms\Components\TextInput::make('tagline')
                            ->nullable()
                            ->maxLength(255)
                            ->placeholder('Transform your retail space with dynamic LED displays'),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->inline(false),
                    ])->columns(2),

                Forms\Components\Section::make('Content')
                    ->schema([
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
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->maxSize(5120)
                            ->disk('public')
                            ->directory('solutions')
                            ->imageEditor()
                            ->imageCropAspectRatio('16:9')

                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),
                    ])->columns(2),

                Forms\Components\Section::make('Benefits')
                    ->schema([
                        Forms\Components\Repeater::make('benefits')
                            ->relationship()
                            ->schema([
                                Forms\Components\Textarea::make('benefit_text')
                                    ->required()
                                    ->rows(2)
                                    ->placeholder('Increase foot traffic with eye-catching displays'),
                                Forms\Components\Hidden::make('order')
                                    ->default(0),
                            ])
                            ->columns(1)
                            ->defaultItems(0)
                            ->addActionLabel('Add Benefit')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => Str::limit($state['benefit_text'] ?? 'New Benefit', 60)),
                    ])->collapsible(),

                Forms\Components\Section::make('Specifications')
                    ->schema([
                        Forms\Components\Repeater::make('specs')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('label')
                                    ->required()
                                    ->maxLength(100)
                                    ->placeholder('Pixel Pitch'),
                                Forms\Components\TextInput::make('value')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('2.5 – 6mm'),
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

                Forms\Components\Section::make('Recommended Products')
                    ->schema([
                        Forms\Components\Select::make('recommendedProducts')
                            ->relationship('recommendedProducts', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->helperText('Select products to recommend for this solution. Pivot data (display name, series, pitch, brightness) is managed via seeders.'),
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
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'retail' => 'success',
                        'outdoor' => 'warning',
                        'corporate' => 'info',
                        'events' => 'primary',
                        'architecture' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('tagline')
                    ->limit(50)
                    ->toggleable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('title', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options(SolutionCategory::options()),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSolutions::route('/'),
            'create' => Pages\CreateSolution::route('/create'),
            'view' => Pages\ViewSolution::route('/{record}'),
            'edit' => Pages\EditSolution::route('/{record}/edit'),
        ];
    }
}
