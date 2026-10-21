<?php

namespace App\Filament\Resources\Dirigentes\Pages;

use App\Filament\Resources\Dirigentes\DirigenteResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDirigente extends EditRecord
{
    protected static string $resource = DirigenteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
