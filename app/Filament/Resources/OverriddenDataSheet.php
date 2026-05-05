<?php

namespace App\Filament\Resources;

use App\Models\RKBU;
use App\Models\TahunAnggaran;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class OverriddenDataSheet implements FromCollection, WithTitle, WithEvents, ShouldAutoSize
{
    public function collection(): Collection
    {
        $tahunAnggaranName = $this->getTahunAnggaranName();

        $rows = collect([
            ["DAFTAR USULAN PENGADAAN BARANG TAHUN {$tahunAnggaranName}"],
            ['PUSKESMAS PANTAI AMAL'],
            ['RUANG : GIGI'],
            [],
            [
                'NO',
                'NAMA BARANG/JENIS BARANG',
                'JUMLAH YANG ADA',
                'B',
                'RR',
                'RB',
                'KEBUTUHAN',
                'KEKURANGAN',
                'SATUAN',
                'HARGA SATUAN',
                'JUMLAH BIAYA',
                'ANALISA',
            ],
        ]);

        $query = RKBU::with(['barang'])->orderBy('id');

        foreach ($query->get() as $index => $row) {
            $rows->push([
                $index + 1,
                $row->barang->nama_barang ?? null,
                $row->jumlah,
                '',
                '',
                '',
                '',
                '',
                $row->barang->satuan ?? null,
                $row->barang->harga_estimasi ?? null,
                $row->total,
                '',
            ]);
        }

        return $rows;
    }

    public function title(): string
    {
        return 'RKBU';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $rowCount = $sheet->getHighestRow();

                $sheet->mergeCells('A1:L1');
                $sheet->mergeCells('A2:L2');
                $sheet->mergeCells('A3:L3');

                $sheet->getStyle('A1:L3')->getFont()->setBold(true);
                $sheet->getStyle('A1:L3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A1:L3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle('A5:L' . $rowCount)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('A5:L5')->getFont()->setBold(true);

                $sheet->getRowDimension(5)->setRowHeight(24);
                $sheet->getStyle('A5:L' . $rowCount)->getAlignment()->setWrapText(true);
            },
        ];
    }

    protected function getTahunAnggaranName(): string
    {
        if ($tahunId = session('tahun_anggaran_id')) {
            return TahunAnggaran::find($tahunId)?->name ?? now()->year;
        }

        return now()->year;
    }
}
