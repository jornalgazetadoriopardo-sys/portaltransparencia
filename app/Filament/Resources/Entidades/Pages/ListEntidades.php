<?php

namespace App\Filament\Resources\Entidades\Pages;

use App\Filament\Resources\Entidades\EntidadeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEntidades extends ListRecords
{
    protected static string $resource = EntidadeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
