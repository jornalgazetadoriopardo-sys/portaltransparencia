<?php

namespace App\Filament\Resources\PerguntaFrequentes\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PerguntaFrequenteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('pergunta')->label('Pergunta')->required()->maxLength(255)->columnSpanFull(),
                Textarea::make('resposta')->label('Resposta')->rows(5)->required()->columnSpanFull(),
                TextInput::make('ordem')->label('Ordem de exibição')->numeric()->default(0),
                Toggle::make('publicado')->label('Publicar no portal')->default(true)->inline(false),
            ]);
    }
}
