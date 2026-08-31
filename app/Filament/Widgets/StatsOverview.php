<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Karyawan', Employee::count())
                ->description('Data karyawan terdaftar')
                ->descriptionIcon('heroicon-o-users')
                ->color('success'),
        ];
    }
}
