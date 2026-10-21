<?php

namespace App\Filament\Resources\PedidoInformacaos\Tables;

use App\Enums\StatusPedido;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PedidoInformacaosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('protocolo')->label('Protocolo')->searchable()->copyable(),
                TextColumn::make('created_at')->label('Recebido em')->dateTime('d/m/Y H:i')->sortable(),
                TextColumn::make('nome')->label('Solicitante')->searchable(),
                TextColumn::make('assunto')->label('Assunto')->searchable()->limit(40),
                TextColumn::make('status')->label('Situação')->badge(),
            ])
            ->filters([
                SelectFilter::make('status')->label('Situação')->options(StatusPedido::class),
            ])
            ->recordActions([
                EditAction::make()->label('Responder'),
            ]);
    }
}
