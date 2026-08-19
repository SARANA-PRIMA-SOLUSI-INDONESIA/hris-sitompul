<?php

namespace App\Filament\Resources\SalaryComponents;

use App\Filament\Resources\SalaryComponents\Pages\CreateSalaryComponent;
use App\Filament\Resources\SalaryComponents\Pages\EditSalaryComponent;
use App\Filament\Resources\SalaryComponents\Pages\ListSalaryComponents;
use App\Filament\Resources\SalaryComponents\Schemas\SalaryComponentForm;
use App\Filament\Resources\SalaryComponents\Tables\SalaryComponentsTable;
use App\Models\SalaryComponent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SalaryComponentResource extends Resource
{
    protected static ?string $model = SalaryComponent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|UnitEnum|null $navigationGroup = 'Penggajian';

    protected static ?string $navigationLabel = 'Komponen Gaji';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Komponen Gaji';

    protected static ?string $pluralModelLabel = 'Komponen Gaji';

    public static function form(Schema $schema): Schema
    {
        return SalaryComponentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalaryComponentsTable::configure($table);
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
            'index' => ListSalaryComponents::route('/'),
            'create' => CreateSalaryComponent::route('/create'),
            'edit' => EditSalaryComponent::route('/{record}/edit'),
        ];
    }
}
