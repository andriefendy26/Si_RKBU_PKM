<?php

namespace App\Filament\Resources\TahunAnggaranResource\Pages;

use App\Filament\Resources\TahunAnggaranResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTahunAnggaran extends CreateRecord
{
    protected static string $resource = TahunAnggaranResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl("index");
    }
}
