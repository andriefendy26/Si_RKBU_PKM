<?php

namespace App\Filament\Resources\TahunAnggaranResource\Pages;

use App\Filament\Resources\TahunAnggaranResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTahunAnggaran extends ListRecords
{
    protected static string $resource = TahunAnggaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
