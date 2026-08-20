<?php

namespace App\Filament\Resources\Employees\Actions;

use App\Models\Employee;
use Filament\Actions\Action;
use Illuminate\Support\Facades\URL;

class ShowQrCardAction
{
    public static function make(): Action
    {
        return Action::make('show_qr_card')
            ->label('Kartu Nama (QR)')
            ->icon('heroicon-o-qr-code')
            ->color('primary')
            ->modalHeading('Kartu Nama Digital')
            ->modalContent(function (Employee $record) {
                $url = URL::route('card.show', ['slug' => $record->slug]);

                return view('filament.resources.employees.qr-modal', [
                    'employee' => $record,
                    'url' => $url,
                ]);
            })
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Tutup')
            ->visible(fn (Employee $record): bool => $record->tampilkan_kartu === true && ! empty($record->slug));
    }
}
