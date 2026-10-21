<?php

namespace App\Filament\Resources\Dirigentes\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DirigenteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('nome')->label('Nome completo')->required()->maxLength(255),
                TextInput::make('cargo')->label('Cargo')->placeholder('Presidente, Tesoureiro...')->required()->maxLength(255),
                Select::make('orgao')
                    ->label('Órgão')
                    ->options([
                        'Diretoria' => 'Diretoria',
                        'Conselho Fiscal' => 'Conselho Fiscal',
                        'Conselho Deliberativo' => 'Conselho Deliberativo',
                        'Coordenação' => 'Coordenação',
                    ])
                    ->default('Diretoria')
                    ->required(),
                TextInput::make('ordem')->label('Ordem de exibição')->numeric()->default(0),
                DatePicker::make('mandato_inicio')->label('Início do mandato'),
                DatePicker::make('mandato_fim')->label('Fim do mandato'),
                Toggle::make('ativo')->label('Exibir no portal (mandato vigente)')->default(true),
            ]);
    }
}
