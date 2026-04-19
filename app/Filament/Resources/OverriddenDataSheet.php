<?php

namespace App\Filament\Resources;

use App\Models\RKBU;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class OverriddenDataSheet implements FromQuery, WithHeadings, WithMapping, WithTitle
{
    public function query(): Builder
    {
        return RKBU::query()->with(['tahunAnggaran', 'barang']);
    }

    public function headings(): array
    {
        return [
            'Tahun Anggaran',
            'Barang',
            'Kode Barang',
            'Jumlah',
            'Total',
        ];
    }

    public function map($row): array
    {
        return [
            $row->tahunAnggaran->name ?? null,
            $row->barang->nama_barang ?? null,
            $row->barang->kode_barang ?? null,
            $row->jumlah,
            $row->total,
        ];
    }

    public function title(): string
    {
        return 'Data RKBU';
    }
}
