<?php

namespace App\Filament\Resources\Dirigentes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DirigentesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('ordem')
            ->columns([
                TextColumn::make('nome')->label('Nome')->searchable()->sortable(),
                TextColumn::make('cargo')->label('Cargo')->searchable(),
                TextColumn::make('orgao')->label('Órgão')->badge(),
                TextColumn::make('mandato_fim')->label('Mandato até')->date('d/m/Y')->sortable(),
                IconColumn::make('ativo')->label('No portal')->boolean(),
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
