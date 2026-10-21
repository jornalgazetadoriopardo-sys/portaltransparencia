<?php

namespace App\Filament\Resources\PedidoInformacaos\Pages;

use App\Filament\Resources\PedidoInformacaos\PedidoInformacaoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPedidoInformacao extends EditRecord
{
    protected static string $resource = PedidoInformacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
