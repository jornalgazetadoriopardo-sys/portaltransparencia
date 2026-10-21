<?php

namespace App\Filament\Resources\Documentos\Tables;

use App\Enums\CategoriaDocumento;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DocumentosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('data_publicacao', 'desc')
            ->columns([
                TextColumn::make('titulo')->label('Título')->searchable()->sortable(),
                TextColumn::make('categoria')->label('Categoria')->badge(),
                TextColumn::make('ano')->label('Ano')->sortable(),
                TextColumn::make('data_publicacao')->label('Publicado em')->date('d/m/Y')->sortable(),
                IconColumn::make('publicado')->label('No portal')->boolean(),
            ])
            ->filters([
                SelectFilter::make('categoria')->label('Categoria')->options(CategoriaDocumento::class),
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
