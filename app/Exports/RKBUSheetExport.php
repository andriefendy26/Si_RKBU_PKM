<?php

namespace App\Exports;

use App\Models\RKBU;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

class RKBUSheetExport implements FromCollection, WithTitle, WithEvents
{
    protected $ruangan;
    protected $tahunAnggaranId;
    protected $tahunAnggaran;

    public function __construct($ruangan, $tahunAnggaranId = null, $tahunAnggaran = null)
    {
        $this->ruangan        = $ruangan;
        $this->tahunAnggaranId = $tahunAnggaranId;
        $this->tahunAnggaran  = $tahunAnggaran ?? ($tahunAnggaranId ? \App\Models\TahunAnggaran::find($tahunAnggaranId)?->name : date('Y'));
    }

    public function collection()
    {
        $query = RKBU::query()->whereHas('user', function ($q) {
            $q->where('name', $this->ruangan);
        });

        if ($this->tahunAnggaranId) {
            $query->where('id_tahun_anggaran', $this->tahunAnggaranId);
        }

        return $query->select(
            'nama_barang',
            'tersedia',
            'kondisi',
            'kebutuhan',
            'kekurangan',
            'satuan',
            'perkiraan_biaya',
            'total',
            'analisa'
        )->get();
    }

    public function title(): string
    {
        return substr($this->ruangan, 0, 31);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet      = $event->sheet;
                $collection = $this->collection();
                $dataCount  = $collection->count();

                // Clear any auto-written rows from FromCollection
                foreach (range(1, $dataCount + 20) as $row) {
                    foreach (range('A', 'N') as $col) {
                        $sheet->setCellValue($col . $row, '');
                    }
                }

                $tahun = $this->tahunAnggaran;

                // ── Row 1: Main title ────────────────────────────────────────
                $sheet->mergeCells('A1:N1');
                $sheet->setCellValue('A1', 'DAFTAR USULAN PENGADAAN BARANG TAHUN ' . $tahun);
                $sheet->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // ── Row 2: Subtitle ──────────────────────────────────────────
                $sheet->mergeCells('A2:N2');
                $sheet->setCellValue('A2', 'PUSKESMAS PANTAI AMAL');
                $sheet->getStyle('A2')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // ── Row 3: Room label ────────────────────────────────────────
                $sheet->mergeCells('A3:N3');
                $sheet->setCellValue('A3', 'Ruang : ' . $this->ruangan);
                $sheet->getStyle('A3')->applyFromArray([
                    'font' => ['bold' => true],
                ]);

                // ── Row 4: Blank spacer ──────────────────────────────────────

                // ── Rows 5–6: Header rows ────────────────────────────────────
                // Column layout (A–N):
                //  A=NO, B=NAMA BARANG/JENIS BARANG, C=JUMLAH YANG ADA,
                //  D=KONDISI B, E=KONDISI RR, F=KONDISI RB,
                //  G=KEBUTUHAN, H=KEKURANGAN, I=SATUAN,
                //  J=HARGA SATUAN, K=JUMLAH BIAYA, L=ANALISA

                // Row 5 – main headers
                $headers = [
                    'A5' => 'NO',
                    'B5' => 'NAMA BARANG/JENIS BARANG',
                    'C5' => 'JUMLAH YANG ADA',
                    'G5' => 'KEBUTUHAN',
                    'H5' => 'KEKURANGAN',
                    'I5' => 'SATUAN',
                    'J5' => 'HARGA SATUAN',
                    'K5' => 'JUMLAH BIAYA',
                    'L5' => 'ANALISA',
                ];

                // Merges for row 5–6 on columns that span both rows
                $sheet->mergeCells('A5:A6');
                $sheet->mergeCells('B5:B6');
                $sheet->mergeCells('C5:C6');
                $sheet->mergeCells('G5:G6');
                $sheet->mergeCells('H5:H6');
                $sheet->mergeCells('I5:I6');
                $sheet->mergeCells('J5:J6');
                $sheet->mergeCells('K5:K6');
                $sheet->mergeCells('L5:L6');

                // KONDISI spans D5:F5
                $sheet->mergeCells('D5:F5');
                $sheet->setCellValue('D5', 'KONDISI');

                // Row 6 – sub-headers for KONDISI
                $sheet->setCellValue('D6', 'B');
                $sheet->setCellValue('E6', 'RR');
                $sheet->setCellValue('F6', 'RB');

                foreach ($headers as $cell => $value) {
                    $sheet->setCellValue($cell, $value);
                }

                $headerStyle = [
                    'font'      => ['bold' => true, 'size' => 9],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                        'wrapText'   => true,
                    ],
                    'fill' => [
                        'fillType'   => Fill::FILL_NONE,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color'       => ['argb' => 'FF000000'],
                        ],
                    ],
                ];

                $sheet->getStyle('A5:L6')->applyFromArray($headerStyle);

                // ── Data rows starting at row 7 ──────────────────────────────
                $startRow = 7;
                $no       = 1;

                foreach ($collection as $row) {
                    $r = $startRow + ($no - 1);

                    // Determine kondisi checkmark columns
                    $isB  = $row->kondisi === 'B'  ? '✓' : '';
                    $isRR = $row->kondisi === 'RR' ? '✓' : '';
                    $isRB = $row->kondisi === 'RB' ? '✓' : '';

                    $sheet->setCellValue('A' . $r, $no);
                    $sheet->setCellValue('B' . $r, $row->nama_barang);
                    $sheet->setCellValue('C' . $r, $row->tersedia);
                    $sheet->setCellValue('D' . $r, $isB);
                    $sheet->setCellValue('E' . $r, $isRR);
                    $sheet->setCellValue('F' . $r, $isRB);
                    $sheet->setCellValue('G' . $r, $row->kebutuhan);
                    $sheet->setCellValue('H' . $r, $row->kekurangan);
                    $sheet->setCellValue('I' . $r, $row->satuan);
                    $sheet->setCellValue('J' . $r, $row->perkiraan_biaya);
                    $sheet->setCellValue('K' . $r, $row->total);
                    $sheet->setCellValue('L' . $r, $row->analisa);

                    // Number format for currency columns
                    $sheet->getStyle('J' . $r)->getNumberFormat()->setFormatCode('#,##0');
                    $sheet->getStyle('K' . $r)->getNumberFormat()->setFormatCode('#,##0');

                    // Center align for certain columns
                    $sheet->getStyle('A' . $r . ':I' . $r)->getAlignment()
                          ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                          ->setVertical(Alignment::VERTICAL_CENTER);

                    // Right-align currency
                    $sheet->getStyle('J' . $r)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle('K' . $r)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                    $no++;
                }

                // Pad to at least 14 rows (rows 7–20)
                $minRows = 14;
                while (($no - 1) < $minRows) {
                    $r = $startRow + ($no - 1);
                    $sheet->setCellValue('A' . $r, $no);
                    $no++;
                }

                $lastRow = $startRow + max($dataCount, $minRows) - 1;

                // ── Apply borders to the entire data table ───────────────────
                $sheet->getStyle('A' . $startRow . ':L' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color'       => ['argb' => 'FF000000'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // ── Column widths ────────────────────────────────────────────
                $sheet->getColumnDimension('A')->setWidth(5);
                $sheet->getColumnDimension('B')->setWidth(30);
                $sheet->getColumnDimension('C')->setWidth(10);
                $sheet->getColumnDimension('D')->setWidth(5);
                $sheet->getColumnDimension('E')->setWidth(5);
                $sheet->getColumnDimension('F')->setWidth(5);
                $sheet->getColumnDimension('G')->setWidth(12);
                $sheet->getColumnDimension('H')->setWidth(12);
                $sheet->getColumnDimension('I')->setWidth(10);
                $sheet->getColumnDimension('J')->setWidth(15);
                $sheet->getColumnDimension('K')->setWidth(15);
                $sheet->getColumnDimension('L')->setWidth(30);

                // ── Row heights ──────────────────────────────────────────────
                $sheet->getRowDimension('5')->setRowHeight(30);
                $sheet->getRowDimension('6')->setRowHeight(20);
                foreach (range($startRow, $lastRow) as $r) {
                    $sheet->getRowDimension($r)->setRowHeight(18);
                }

                // ── Global font ──────────────────────────────────────────────
                $sheet->getParent()->getDefaultStyle()->getFont()->setName('Arial')->setSize(9);
            },
        ];
    }
}