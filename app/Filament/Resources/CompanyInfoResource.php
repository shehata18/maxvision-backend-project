<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyInfoResource\Pages;
use App\Models\CompanyInfo;
use Filament\Resources\Resource;

class CompanyInfoResource extends Resource
{
    protected static ?string $model = CompanyInfo::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Company Info';

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCompanyInfo::route('/'),
        ];
    }
}
