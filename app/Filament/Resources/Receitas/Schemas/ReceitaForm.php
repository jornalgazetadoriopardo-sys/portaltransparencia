<?php

namespace App\Filament\Resources\Receitas\Schemas;

use App\Enums\FonteReceita;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ReceitaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                DatePicker::make('data')->label('Data do recebimento')->default(now())->required(),
                Select::make('fonte')->label('Fonte')->options(FonteReceita::class)->required(),
                Select::make('parceria_id')->label('Parceria vinculada (se houver)')->relationship('parceria', 'numero')->searchable()->preload(),
                TextInput::make('valor')->label('Valor')->prefix('R$')->numeric()->minValue(0)->required(),
                TextInput::make('descricao')->label('Descrição')->placeholder('1ª parcela do Termo de Colaboração 001/2026')->required()->maxLength(255)->columnSpanFull(),
                FileUpload::make('comprovante')->label('Comprovante (opcional)')->disk('public')->directory('receitas')->acceptedFileTypes(['application/pdf', 'image/*'])->maxSize(10240)->downloadable()->columnSpanFull(),
            ]);
    }
}
