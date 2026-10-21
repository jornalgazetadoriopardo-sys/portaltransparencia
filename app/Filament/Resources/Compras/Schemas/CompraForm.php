<?php

namespace App\Filament\Resources\Compras\Schemas;

use App\Enums\ModalidadeCompra;
use App\Enums\SituacaoCompra;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CompraForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('numero')->label('Número do processo')->placeholder('COT-001/2026')->required()->maxLength(255),
                Select::make('modalidade')->label('Modalidade')->options(ModalidadeCompra::class)->required(),
                DatePicker::make('data_abertura')->label('Data de abertura')->default(now())->required(),
                Select::make('situacao')->label('Situação')->options(SituacaoCompra::class)->default(SituacaoCompra::Aberta)->required(),
                Textarea::make('objeto')->label('Objeto')->rows(3)->required()->columnSpanFull(),
                Select::make('parceria_id')->label('Parceria vinculada (se houver)')->relationship('parceria', 'numero')->searchable()->preload(),
                TextInput::make('valor')->label('Valor contratado')->prefix('R$')->numeric()->minValue(0),
                TextInput::make('fornecedor')->label('Fornecedor vencedor')->maxLength(255),
                TextInput::make('fornecedor_documento')->label('CNPJ / CPF do fornecedor')->maxLength(18),
                FileUpload::make('arquivo')->label('Edital, cotações ou contrato (PDF)')->disk('public')->directory('compras')->acceptedFileTypes(['application/pdf'])->maxSize(20480)->downloadable()->columnSpanFull(),
            ]);
    }
}
