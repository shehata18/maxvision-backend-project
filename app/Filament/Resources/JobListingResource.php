<?php

namespace App\Filament\Resources;

use App\Enums\JobCategory;
use App\Enums\JobLocation;
use App\Enums\JobType;
use App\Filament\Resources\JobListingResource\Pages;
use App\Models\JobListing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class JobListingResource extends Resource
{
    protected static ?string $model = JobListing::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Company';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'title';

    protected static int $globalSearchResultsLimit = 5;

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'department', 'category'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Department' => $record->department,
            'Location' => $record->location,
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
                                if (! $get('slug') || $get('slug') === Str::slug($get('title') . '-' . Str::random(6))) {
                                    $set('slug', Str::slug($state . '-' . Str::random(6)));
                                }
                            }),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Auto-generated from title'),
                        Forms\Components\TextInput::make('department')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Engineering'),
                        Forms\Components\Select::make('category')
                            ->required()
                            ->options(JobCategory::options())
                            ->searchable(),
                        Forms\Components\Select::make('job_type')
                            ->required()
                            ->label('Job Type')
                            ->options(JobType::options())
                            ->searchable(),
                        Forms\Components\Select::make('location')
                            ->required()
                            ->options(JobLocation::options())
                            ->searchable(),
                    ])->columns(2),

                Forms\Components\Section::make('Job Details')
                    ->schema([
                        Forms\Components\Textarea::make('summary')
                            ->required()
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('Brief overview of the position...')
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('description')
                            ->nullable()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'bulletList',
                                'orderedList',
                                'link',
                                'h2',
                                'h3',
                            ])
                            ->columnSpanFull()
                            ->placeholder('Full job description...'),
                        Forms\Components\TextInput::make('salary_range')
                            ->nullable()
                            ->maxLength(100)
                            ->placeholder('$80,000 - $120,000 CAD')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Requirements')
                    ->schema([
                        Forms\Components\Repeater::make('requirements')
                            ->schema([
                                Forms\Components\Textarea::make('requirement')
                                    ->required()
                                    ->rows(2)
                                    ->placeholder('5+ years of experience with LED display technology'),
                            ])
                            ->columns(1)
                            ->defaultItems(0)
                            ->addActionLabel('Add Requirement')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => Str::limit($state['requirement'] ?? 'New Requirement', 60)),
                    ])->collapsible(),

                Forms\Components\Section::make('Benefits')
                    ->schema([
                        Forms\Components\Repeater::make('benefits')
                            ->schema([
                                Forms\Components\TextInput::make('benefit')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Competitive salary and benefits package'),
                            ])
                            ->columns(1)
                            ->defaultItems(0)
                            ->addActionLabel('Add Benefit')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['benefit'] ?? 'New Benefit'),
                    ])->collapsible(),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->inline(false)
                            ->helperText('Inactive jobs are hidden from the careers page.'),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured')
                            ->default(false)
                            ->inline(false)
                            ->helperText('Featured jobs are highlighted at the top of the list.'),
                        Forms\Components\DatePicker::make('posted_at')
                            ->label('Posted Date')
                            ->default(now())
                            ->nullable(),
                        Forms\Components\DatePicker::make('deadline')
                            ->label('Application Deadline')
                            ->nullable()
                            ->afterOrEqual('posted_at'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(40),
                Tables\Columns\TextColumn::make('department')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'engineering' => 'success',
                        'sales' => 'warning',
                        'marketing' => 'info',
                        'operations' => 'primary',
                        'customer_support' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('location')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('job_type')
                    ->label('Type')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),
                Tables\Columns\TextColumn::make('posted_at')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('deadline')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('posted_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options(JobCategory::options()),
                Tables\Filters\SelectFilter::make('job_type')
                    ->label('Job Type')
                    ->options(JobType::options()),
                Tables\Filters\SelectFilter::make('location')
                    ->options(JobLocation::options()),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All')
                    ->trueLabel('Active')
                    ->falseLabel('Inactive'),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured')
                    ->placeholder('All')
                    ->trueLabel('Featured')
                    ->falseLabel('Not Featured'),
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
            'index' => Pages\ListJobListings::route('/'),
            'create' => Pages\CreateJobListing::route('/create'),
            'view' => Pages\ViewJobListing::route('/{record}'),
            'edit' => Pages\EditJobListing::route('/{record}/edit'),
        ];
    }
}
