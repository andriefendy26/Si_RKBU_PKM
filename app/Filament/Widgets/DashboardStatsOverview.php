<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $tahunAnggaranId = session('tahun_anggaran_id');

        $queryRkbu = \App\Models\RKBU::query();

        if ($tahunAnggaranId) {
            $queryRkbu->where('id_tahun_anggaran', $tahunAnggaranId);
        }
        $totalRkbuItems = $queryRkbu->sum('jumlah');
        $totalRkbuValue = $queryRkbu->sum('total');

        return [
            Stat::make('Total Item RKBU', (int) $totalRkbuItems)
                ->description('Jumlah item dalam RKBU')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('warning'),

            Stat::make('Estimasi Nilai RKBU', 'Rp ' . number_format((float) $totalRkbuValue, 2, ',', '.'))
                ->description('Total nilai pengajuan RKBU')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('danger'),
        ];
    }
}
