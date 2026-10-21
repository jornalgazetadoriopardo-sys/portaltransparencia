<?php

namespace App\Filament\Resources\Entidades\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EntidadesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nome')->label('Razão social'),
                TextColumn::make('cnpj')->label('CNPJ'),
                TextColumn::make('updated_at')->label('Atualizado em')->dateTime('d/m/Y H:i'),
            ])
            ->paginated(false)
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
