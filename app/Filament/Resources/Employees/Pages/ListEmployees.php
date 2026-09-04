<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Resources\Employees\EmployeeResource;
use App\Filament\Resources\Employees\Exports\EmployeeExporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Resources\Pages\ListRecords;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ExportAction::make()
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->exporter(EmployeeExporter::class)
                ->formats([ExportFormat::Xlsx]),
        ];
    }
}
