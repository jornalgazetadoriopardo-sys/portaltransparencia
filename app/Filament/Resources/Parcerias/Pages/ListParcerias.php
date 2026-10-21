<?php

namespace App\Filament\Resources\Parcerias\Pages;

use App\Filament\Resources\Parcerias\ParceriaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListParcerias extends ListRecords
{
    protected static string $resource = ParceriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
