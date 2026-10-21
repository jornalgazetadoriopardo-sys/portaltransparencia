<?php

namespace App\Filament\Resources\Parcerias\Tables;

use App\Enums\SituacaoParceria;
use App\Enums\SituacaoPrestacao;
use App\Enums\TipoParceria;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ParceriasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('data_assinatura', 'desc')
            ->columns([
                TextColumn::make('numero')->label('Número')->searchable()->sortable(),
                TextColumn::make('tipo')->label('Tipo')->badge(),
                TextColumn::make('orgao_concedente')->label('Concedente')->searchable()->limit(40),
                TextColumn::make('data_assinatura')->label('Assinatura')->date('d/m/Y')->sortable(),
                TextColumn::make('valor_total')->label('Valor total')->money('BRL', locale: 'pt_BR')->sortable(),
                TextColumn::make('valor_liberado')->label('Liberado')->money('BRL', locale: 'pt_BR'),
                TextColumn::make('situacao')->label('Situação')->badge(),
                TextColumn::make('prestacao_situacao')->label('Prestação de contas')->badge(),
                IconColumn::make('publicado')->label('No portal')->boolean(),
            ])
            ->filters([
                SelectFilter::make('tipo')->label('Tipo')->options(TipoParceria::class),
                SelectFilter::make('situacao')->label('Situação')->options(SituacaoParceria::class),
                SelectFilter::make('prestacao_situacao')->label('Prestação de contas')->options(SituacaoPrestacao::class),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
