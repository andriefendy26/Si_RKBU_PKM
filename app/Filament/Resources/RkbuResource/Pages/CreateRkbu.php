<?php

namespace App\Filament\Resources\RkbuResource\Pages;

use App\Filament\Resources\RkbuResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRkbu extends CreateRecord
{
    protected static string $resource = RkbuResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl("index");
    }
}
