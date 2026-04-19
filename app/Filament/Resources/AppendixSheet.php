<?php

namespace App\Filament\Resources;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;

class AppendixSheet implements FromCollection, WithTitle
{
    public function collection(): Collection
    {
        return collect([
            ['Catatan'],
            ['Data ini diekspor dari aplikasi RKBU.'],
        ]);
    }

    public function title(): string
    {
        return 'Appendix';
    }
}
