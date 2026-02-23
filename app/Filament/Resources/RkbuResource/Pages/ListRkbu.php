<?php

namespace App\Filament\Resources\RkbuResource\Pages;

use App\Filament\Resources\RkbuResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRkbu extends ListRecords
{
    protected static string $resource = RkbuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
