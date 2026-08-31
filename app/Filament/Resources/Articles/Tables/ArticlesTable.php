<?php

namespace App\Filament\Resources\Articles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;

class ArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('gambar')->label('Gambar')->square(),
            TextColumn::make('judul')->label('Judul')->searchable()->sortable()->limit(45),
            IconColumn::make('diterbitkan')->label('Terbit')->boolean(),
            TextColumn::make('diterbitkan_pada')->label('Publikasi')->dateTime('d M Y')->sortable(),
            TextColumn::make('user.name')->label('Penulis')->placeholder('-'),
        ])->defaultSort('created_at', 'desc')->recordActions([EditAction::make()])->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
