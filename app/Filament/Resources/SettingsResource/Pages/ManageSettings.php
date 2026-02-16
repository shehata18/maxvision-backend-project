<?php

namespace App\Filament\Resources\SettingsResource\Pages;

use App\Filament\Resources\SettingsResource;
use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = SettingsResource::class;

    protected static string $view = 'filament.resources.settings-resource.pages.manage-settings';

    protected static ?string $title = 'Manage Site Settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = Setting::getAll();

        // Decode hero_stats if it's a JSON string
        $heroStats = [];
        if (isset($settings['hero_stats'])) {
            $heroStats = json_decode($settings['hero_stats'], true) ?? [];
        }

        $this->form->fill([
            'site_name' => $settings['site_name'] ?? 'MaxVision Display Inc.',
            'site_tagline' => $settings['site_tagline'] ?? '',
            'site_description' => $settings['site_description'] ?? '',
            'contact_phone' => $settings['contact_phone'] ?? '',
            'contact_email' => $settings['contact_email'] ?? '',
            'contact_address' => $settings['contact_address'] ?? '',
            'social_linkedin' => $settings['social_linkedin'] ?? '',
            'social_youtube' => $settings['social_youtube'] ?? '',
            'social_twitter' => $settings['social_twitter'] ?? '',
            'hero_title' => $settings['hero_title'] ?? '',
            'hero_subtitle' => $settings['hero_subtitle'] ?? '',
            'hero_stats' => $heroStats,
            'footer_about' => $settings['footer_about'] ?? '',
            'footer_copyright' => $settings['footer_copyright'] ?? '',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General Settings')
                    ->description('Basic site information')
                    ->icon('heroicon-o-globe-alt')
                    ->schema([
                        Forms\Components\TextInput::make('site_name')
                            ->required()
                            ->maxLength(255)
                            ->default('MaxVision Display Inc.'),
                        Forms\Components\TextInput::make('site_tagline')
                            ->nullable()
                            ->maxLength(255)
                            ->placeholder('High-Performance LED Display Solutions'),
                        Forms\Components\Textarea::make('site_description')
                            ->nullable()
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Contact Information')
                    ->description('Company contact details displayed on the website')
                    ->icon('heroicon-o-phone')
                    ->schema([
                        Forms\Components\TextInput::make('contact_phone')
                            ->required()
                            ->maxLength(50)
                            ->placeholder('1-888-LED-PROS'),
                        Forms\Components\TextInput::make('contact_email')
                            ->required()
                            ->email()
                            ->maxLength(255)
                            ->placeholder('sales@maxvisiondisplay.com'),
                        Forms\Components\Textarea::make('contact_address')
                            ->required()
                            ->rows(2)
                            ->placeholder('123 Technology Drive, Toronto, ON M5V 1A1, Canada')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Social Media Links')
                    ->description('Social media profile URLs')
                    ->icon('heroicon-o-share')
                    ->schema([
                        Forms\Components\TextInput::make('social_linkedin')
                            ->nullable()
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://linkedin.com/company/maxvision')
                            ->prefixIcon('heroicon-o-link'),
                        Forms\Components\TextInput::make('social_youtube')
                            ->nullable()
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://youtube.com/@maxvision')
                            ->prefixIcon('heroicon-o-link'),
                        Forms\Components\TextInput::make('social_twitter')
                            ->nullable()
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://twitter.com/maxvision')
                            ->prefixIcon('heroicon-o-link'),
                    ])->columns(3),

                Forms\Components\Section::make('Hero Section Content')
                    ->description('Homepage hero section content')
                    ->icon('heroicon-o-sparkles')
                    ->schema([
                        Forms\Components\TextInput::make('hero_title')
                            ->nullable()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('hero_subtitle')
                            ->nullable()
                            ->rows(2)
                            ->columnSpanFull(),
                        Forms\Components\Repeater::make('hero_stats')
                            ->schema([
                                Forms\Components\TextInput::make('label')
                                    ->required()
                                    ->maxLength(100),
                                Forms\Components\TextInput::make('value')
                                    ->required()
                                    ->maxLength(50),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Add Stat')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => ($state['value'] ?? '') . ' ' . ($state['label'] ?? 'New Stat'))
                            ->columnSpanFull(),
                    ])->columns(2)->collapsible(),

                Forms\Components\Section::make('Footer Content')
                    ->description('Footer section information')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Forms\Components\RichEditor::make('footer_about')
                            ->nullable()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'link',
                            ])
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('footer_copyright')
                            ->nullable()
                            ->maxLength(255)
                            ->default('© 2024 Maxvision Display Inc. All rights reserved.'),
                    ])->columns(2)->collapsible(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Simple string/text settings
        $stringSettings = [
            'site_name', 'site_tagline', 'contact_phone', 'contact_email',
            'social_linkedin', 'social_youtube', 'social_twitter',
            'hero_title', 'footer_copyright',
        ];

        $textSettings = [
            'site_description', 'contact_address', 'hero_subtitle', 'footer_about',
        ];

        foreach ($stringSettings as $key) {
            Setting::set($key, $data[$key] ?? null, 'string');
        }

        foreach ($textSettings as $key) {
            Setting::set($key, $data[$key] ?? null, 'text');
        }

        // Handle hero_stats as JSON
        Setting::set('hero_stats', json_encode($data['hero_stats'] ?? []), 'json');

        Notification::make()
            ->title('Site settings updated successfully')
            ->success()
            ->send();
    }

    public function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->submit('save'),
        ];
    }
}
