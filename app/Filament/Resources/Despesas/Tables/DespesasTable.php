<?php

namespace App\Filament\Resources\Despesas\Tables;

use App\Enums\CategoriaDespesa;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DespesasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('data', 'desc')
            ->columns([
                TextColumn::make('data')->label('Data')->date('d/m/Y')->sortable(),
                TextColumn::make('categoria')->label('Categoria')->badge(),
                TextColumn::make('favorecido')->label('Favorecido')->searchable(),
                TextColumn::make('descricao')->label('Descrição')->searchable()->limit(40),
                TextColumn::make('parceria.numero')->label('Parceria'),
                TextColumn::make('valor')->label('Valor')->money('BRL', locale: 'pt_BR')->sortable()
                    ->summarize(Sum::make()->label('Total')->money('BRL', locale: 'pt_BR')),
            ])
            ->filters([
                SelectFilter::make('categoria')->label('Categoria')->options(CategoriaDespesa::class),
                SelectFilter::make('parceria')->label('Parceria')->relationship('parceria', 'numero'),
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
