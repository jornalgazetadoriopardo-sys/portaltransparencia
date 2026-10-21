<?php

namespace App\Filament\Resources\PedidoInformacaos;

use App\Enums\StatusPedido;
use App\Filament\Resources\PedidoInformacaos\Pages\EditPedidoInformacao;
use App\Filament\Resources\PedidoInformacaos\Pages\ListPedidoInformacaos;
use App\Filament\Resources\PedidoInformacaos\Schemas\PedidoInformacaoForm;
use App\Filament\Resources\PedidoInformacaos\Tables\PedidoInformacaosTable;
use App\Models\PedidoInformacao;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PedidoInformacaoResource extends Resource
{
    protected static ?string $model = PedidoInformacao::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static string|UnitEnum|null $navigationGroup = 'Atendimento';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'pedido de informação';

    protected static ?string $pluralModelLabel = 'Pedidos de informação';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $slug = 'pedidos-informacao';

    protected static ?string $recordTitleAttribute = 'protocolo';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $pendentes = PedidoInformacao::query()
            ->whereIn('status', [StatusPedido::Recebido, StatusPedido::EmAnalise])
            ->count();

        return $pendentes > 0 ? (string) $pendentes : null;
    }

    public static function form(Schema $schema): Schema
    {
        return PedidoInformacaoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PedidoInformacaosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPedidoInformacaos::route('/'),
            'edit' => EditPedidoInformacao::route('/{record}/edit'),
        ];
    }
}
