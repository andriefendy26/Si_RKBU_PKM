<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class RkbuChart extends ChartWidget
{
    protected ?string $heading = 'Total Nilai RKBU per Kategori';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $tahunAnggaranId = session('tahun_anggaran_id');

        $query = \App\Models\RKBU::query()
            ->join('barang', 'RKBU.id_barang', '=', 'barang.id')
            ->join('kategori_barang', 'barang.id_kategori', '=', 'kategori_barang.id')
            ->selectRaw('kategori_barang.name as kategori, SUM(RKBU.total) as total_nilai')
            ->groupBy('kategori_barang.id', 'kategori_barang.name');

        if ($tahunAnggaranId) {
            $query->where('RKBU.id_tahun_anggaran', $tahunAnggaranId);
        }

        $data = $query->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Nilai RKBU (Rp)',
                    'data' => $data->pluck('total_nilai')->toArray(),
                    'backgroundColor' => ['#f59e0b', '#3b82f6', '#10b981', '#ef4444', '#8b5cf6'],
                ],
            ],
            'labels' => $data->pluck('kategori')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
