<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Leave;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $today = now()->toDateString();

        return [
            Stat::make('Total Karyawan Aktif', Employee::whereNull('tanggal_keluar')->count())
                ->description('Karyawan yang masih aktif')
                ->descriptionIcon('heroicon-o-users')
                ->color('success'),

            Stat::make('Hadir Hari Ini', Attendance::whereDate('tanggal', $today)->where('status', Attendance::STATUS_HADIR)->count())
                ->description('Karyawan hadir hari ini')
                ->descriptionIcon('heroicon-o-check-badge')
                ->color('primary'),

            Stat::make('Cuti Menunggu', Leave::where('status', Leave::STATUS_MENUNGGU)->count())
                ->description('Menunggu persetujuan')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Alpha Hari Ini', Attendance::whereDate('tanggal', $today)->where('status', Attendance::STATUS_ALPHA)->count())
                ->description('Tanpa keterangan hari ini')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger'),
        ];
    }
}
