<?php

namespace App\Filament\Resources\RkbuResource\Pages;

use App\Filament\Resources\RkbuResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRkbu extends EditRecord
{
    protected static string $resource = RkbuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl("index");
    }
}
