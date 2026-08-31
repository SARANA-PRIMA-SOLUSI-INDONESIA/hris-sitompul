<?php

namespace App\Filament\Resources\SiteSettings\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SiteSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('logo')->label('Logo')->square(),
            TextColumn::make('nama_komunitas')->label('Nama komunitas')->searchable(),
            TextColumn::make('tagline')->label('Tagline')->placeholder('-'),
            TextColumn::make('updated_at')->label('Terakhir diubah')->dateTime('d M Y H:i'),
        ])->recordActions([EditAction::make()]);
    }
}
