<?php

namespace App\Filament\Resources\Employees\Actions;

use App\Models\Employee;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Toggle;

class ConfigureQrCardAction
{
    public static function make(): Action
    {
        return Action::make('configure_qr_card')
            ->label('Atur QR Card')
            ->icon('heroicon-o-eye')
            ->color('gray')
            ->modalHeading('Pengaturan QR Card')
            ->fillForm(fn (Employee $record): array => [
                'tampilkan_kartu' => $record->tampilkan_kartu,
                'visibilitas_field' => $record->visibleQrFields(),
            ])
            ->form([
                Toggle::make('tampilkan_kartu')
                    ->label('Aktifkan QR Card')
                    ->helperText('Nonaktifkan jika QR Card tidak boleh diakses.'),
                CheckboxList::make('visibilitas_field')
                    ->label('Kolom yang ditampilkan di QR Card')
                    ->options(Employee::qrFieldLabels())
                    ->columns(2)
                    ->gridDirection('row')
                    ->required()
                    ->helperText('Centang kolom yang boleh dilihat saat QR dipindai.'),
            ])
            ->action(function (Employee $record, array $data): void {
                $record->update([
                    'tampilkan_kartu' => $data['tampilkan_kartu'] ?? false,
                    'visibilitas_field' => $data['visibilitas_field'] ?? [],
                ]);
            });
    }
}
