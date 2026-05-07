<?php

namespace App\Exports;

use App\Models\RKBU;
use App\Models\TahunAnggaran;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RKBUExport implements WithMultipleSheets
{
    protected $tahunAnggaranId;

    public function __construct($tahunAnggaranId = null)
    {
        $this->tahunAnggaranId = $tahunAnggaranId;
    }

    public function sheets(): array
    {
        $sheets = [];

        // Resolve the year label for the header title
        $tahunAnggaran = $this->tahunAnggaranId
            ? TahunAnggaran::find($this->tahunAnggaranId)?->name
            : date('Y');

        $query = RKBU::with('user');

        if ($this->tahunAnggaranId) {
            $query->where('id_tahun_anggaran', $this->tahunAnggaranId);
        }

        $rkbuData = $query->get();

        // Unique user names
        $userList = $rkbuData->pluck('user.name')->filter()->unique();

        foreach ($userList as $userName) {
            $sheets[] = new RKBUSheetExport($userName, $this->tahunAnggaranId, $tahunAnggaran);
        }

        return $sheets;
    }
}