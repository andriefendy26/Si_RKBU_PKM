<?php

namespace App\Filament\Resources;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;

class CoverSheet implements FromCollection, WithTitle
{
    public function collection(): Collection
    {
        return collect([
            ['Laporan RKBU'],
            ['Tanggal Export', now()->format('Y-m-d H:i:s')],
            ['Tahun Anggaran', session('tahun_anggaran_id') ?: 'Semua'],
        ]);
    }

    public function title(): string
    {
        return 'Cover';
    }
}
