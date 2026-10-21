<?php

namespace App\Filament\Resources\Parcerias\Pages;

use App\Filament\Resources\Parcerias\ParceriaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditParceria extends EditRecord
{
    protected static string $resource = ParceriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
