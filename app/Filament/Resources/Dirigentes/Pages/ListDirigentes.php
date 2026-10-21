<?php

namespace App\Filament\Resources\Dirigentes\Pages;

use App\Filament\Resources\Dirigentes\DirigenteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDirigentes extends ListRecords
{
    protected static string $resource = DirigenteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
