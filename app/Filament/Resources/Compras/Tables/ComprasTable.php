<?php

namespace App\Filament\Resources\Compras\Tables;

use App\Enums\ModalidadeCompra;
use App\Enums\SituacaoCompra;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ComprasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('data_abertura', 'desc')
            ->columns([
                TextColumn::make('numero')->label('Número')->searchable()->sortable(),
                TextColumn::make('modalidade')->label('Modalidade')->badge(),
                TextColumn::make('objeto')->label('Objeto')->searchable()->limit(50),
                TextColumn::make('data_abertura')->label('Abertura')->date('d/m/Y')->sortable(),
                TextColumn::make('situacao')->label('Situação')->badge(),
                TextColumn::make('valor')->label('Valor')->money('BRL', locale: 'pt_BR'),
            ])
            ->filters([
                SelectFilter::make('modalidade')->label('Modalidade')->options(ModalidadeCompra::class),
                SelectFilter::make('situacao')->label('Situação')->options(SituacaoCompra::class),
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
