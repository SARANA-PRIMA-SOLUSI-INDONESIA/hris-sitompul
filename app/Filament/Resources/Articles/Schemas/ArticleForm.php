<?php

namespace App\Filament\Resources\Articles\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Informasi artikel')->schema([
                TextInput::make('judul')->label('Judul')->required()->live(onBlur: true)->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                TextInput::make('slug')->label('Slug URL')->required()->unique(ignoreRecord: true),
                TextInput::make('ringkasan')->label('Ringkasan')->maxLength(255),
                FileUpload::make('gambar')->label('Gambar utama')->image()->directory('article-images')->imageEditor(),
                RichEditor::make('isi')->label('Isi konten')->required()->columnSpanFull(),
            ])->columns(2),
            Section::make('Publikasi')->schema([
                Toggle::make('diterbitkan')->label('Terbitkan artikel')->default(false),
                DateTimePicker::make('diterbitkan_pada')->label('Tanggal publikasi')->default(now()),
            ])->columns(2),
        ]);
    }
}
