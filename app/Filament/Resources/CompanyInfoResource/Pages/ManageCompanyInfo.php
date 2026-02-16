<?php

namespace App\Filament\Resources\CompanyInfoResource\Pages;

use App\Filament\Resources\CompanyInfoResource;
use App\Models\CompanyInfo;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Str;

class ManageCompanyInfo extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = CompanyInfoResource::class;

    protected static string $view = 'filament.resources.company-info-resource.pages.manage-company-info';

    protected static ?string $title = 'Manage Company Info';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'milestones' => CompanyInfo::getMilestones(),
            'team_members' => CompanyInfo::getTeam(),
            'certifications' => CompanyInfo::getCertifications(),
            'partners' => CompanyInfo::getPartners(),
            'stats' => CompanyInfo::getStats(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Milestones')
                    ->description('Company history timeline entries')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Forms\Components\Repeater::make('milestones')
                            ->schema([
                                Forms\Components\TextInput::make('year')
                                    ->required()
                                    ->maxLength(4)
                                    ->placeholder('2008'),
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Founded in Shenzhen'),
                                Forms\Components\Textarea::make('description')
                                    ->required()
                                    ->rows(3),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Add Milestone')
                            ->collapsible()
                            ->reorderable()
                            ->itemLabel(fn (array $state): ?string => ($state['year'] ?? '') . ' - ' . ($state['title'] ?? 'New Milestone')),
                    ])->collapsible(),

                Forms\Components\Section::make('Team Members')
                    ->description('Leadership and key team members')
                    ->icon('heroicon-o-user-group')
                    ->schema([
                        Forms\Components\Repeater::make('team_members')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('role')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('bio')
                                    ->required()
                                    ->rows(3),
                                Forms\Components\TextInput::make('initials')
                                    ->required()
                                    ->maxLength(5)
                                    ->placeholder('DC'),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Add Team Member')
                            ->collapsible()
                            ->reorderable()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? 'New Team Member'),
                    ])->collapsible(),

                Forms\Components\Section::make('Certifications')
                    ->description('Industry certifications and compliance')
                    ->icon('heroicon-o-shield-check')
                    ->schema([
                        Forms\Components\Repeater::make('certifications')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('UL Listed'),
                                Forms\Components\Textarea::make('description')
                                    ->required()
                                    ->rows(2),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Add Certification')
                            ->collapsible()
                            ->reorderable()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? 'New Certification'),
                    ])->collapsible(),

                Forms\Components\Section::make('Technology Partners')
                    ->description('Partner companies and suppliers')
                    ->icon('heroicon-o-link')
                    ->schema([
                        Forms\Components\Repeater::make('partners')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Novastar'),
                                Forms\Components\FileUpload::make('logo')
                                    ->nullable()
                                    ->image()
                                    ->maxSize(2048)
                                    ->disk('public')
                                    ->directory('partners'),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Add Partner')
                            ->collapsible()
                            ->reorderable()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? 'New Partner'),
                    ])->collapsible(),

                Forms\Components\Section::make('Company Stats')
                    ->description('Key company statistics displayed on the website')
                    ->icon('heroicon-o-chart-bar')
                    ->schema([
                        Forms\Components\Repeater::make('stats')
                            ->schema([
                                Forms\Components\TextInput::make('value')
                                    ->required()
                                    ->maxLength(50)
                                    ->placeholder('1,500+'),
                                Forms\Components\TextInput::make('label')
                                    ->required()
                                    ->maxLength(100)
                                    ->placeholder('Installations'),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Add Stat')
                            ->reorderable()
                            ->itemLabel(fn (array $state): ?string => ($state['value'] ?? '') . ' ' . ($state['label'] ?? 'New Stat')),
                    ])->collapsible(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        CompanyInfo::updateKey('milestones', $data['milestones'] ?? []);
        CompanyInfo::updateKey('team_members', $data['team_members'] ?? []);
        CompanyInfo::updateKey('certifications', $data['certifications'] ?? []);
        CompanyInfo::updateKey('partners', $data['partners'] ?? []);
        CompanyInfo::updateKey('stats', $data['stats'] ?? []);

        Notification::make()
            ->title('Company info updated successfully')
            ->success()
            ->send();
    }

    public function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Changes')
                ->submit('save'),
        ];
    }
}
