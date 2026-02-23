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

        $queryBarang = \App\Models\Barang::query();
        $queryRkbu = \App\Models\RKBU::query();

        if ($tahunAnggaranId) {
            $queryBarang->where('id_tahun_anggaran', $tahunAnggaranId);
            $queryRkbu->where('id_tahun_anggaran', $tahunAnggaranId);
        }

        $totalBarang = $queryBarang->count();
        $aktifBarang = (clone $queryBarang)->where('is_active', true)->count();
        
        $totalRkbuItems = $queryRkbu->sum('jumlah');
        $totalRkbuValue = $queryRkbu->sum('total');

        return [
            Stat::make('Total Barang', $totalBarang)
                ->description('Barang yang terdaftar')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary'),

            Stat::make('Barang Aktif', $aktifBarang)
                ->description('Barang status aktif')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

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
