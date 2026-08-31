<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('nama_komunitas')->label('Nama komunitas')->required(),
            TextInput::make('tagline')->label('Tagline'),
            FileUpload::make('logo')->label('Logo komunitas')->image()->directory('site')->imageEditor(),
            TextInput::make('telepon')->label('Nomor telepon')->tel(),
            TextInput::make('email')->label('Email')->email(),
            Textarea::make('tentang')->label('Tentang komunitas')->rows(5)->columnSpanFull(),
        ]);
    }
}
