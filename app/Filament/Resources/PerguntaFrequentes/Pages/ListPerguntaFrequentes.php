<?php

namespace App\Filament\Resources\PerguntaFrequentes\Pages;

use App\Filament\Resources\PerguntaFrequentes\PerguntaFrequenteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPerguntaFrequentes extends ListRecords
{
    protected static string $resource = PerguntaFrequenteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
