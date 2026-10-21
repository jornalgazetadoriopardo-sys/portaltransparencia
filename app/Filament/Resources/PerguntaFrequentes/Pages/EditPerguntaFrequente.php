<?php

namespace App\Filament\Resources\PerguntaFrequentes\Pages;

use App\Filament\Resources\PerguntaFrequentes\PerguntaFrequenteResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPerguntaFrequente extends EditRecord
{
    protected static string $resource = PerguntaFrequenteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
