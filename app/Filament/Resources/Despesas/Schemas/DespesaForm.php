<?php

namespace App\Filament\Resources\Despesas\Schemas;

use App\Enums\CategoriaDespesa;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DespesaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                DatePicker::make('data')->label('Data do pagamento')->default(now())->required(),
                Select::make('categoria')->label('Categoria')->options(CategoriaDespesa::class)->required(),
                TextInput::make('favorecido')->label('Favorecido (fornecedor ou pessoa)')->required()->maxLength(255),
                TextInput::make('favorecido_documento')
                    ->label('CNPJ ou CPF do favorecido')
                    ->helperText('No portal, o CPF aparece mascarado (LGPD).')
                    ->maxLength(18),
                TextInput::make('documento_fiscal')->label('Nota fiscal / recibo nº')->maxLength(255),
                Select::make('parceria_id')->label('Paga com recursos da parceria')->relationship('parceria', 'numero')->searchable()->preload(),
                TextInput::make('descricao')->label('Descrição')->required()->maxLength(255),
                TextInput::make('valor')->label('Valor')->prefix('R$')->numeric()->minValue(0)->required(),
                FileUpload::make('comprovante')->label('Comprovante (opcional)')->disk('public')->directory('despesas')->acceptedFileTypes(['application/pdf', 'image/*'])->maxSize(10240)->downloadable()->columnSpanFull(),
            ]);
    }
}
