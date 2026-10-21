<?php

namespace App\Filament\Resources\PedidoInformacaos\Schemas;

use App\Enums\StatusPedido;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PedidoInformacaoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Pedido recebido')
                    ->columns(2)
                    ->schema([
                        TextInput::make('protocolo')->label('Protocolo')->disabled()->dehydrated(false),
                        TextInput::make('assunto')->label('Assunto')->disabled()->dehydrated(false),
                        TextInput::make('nome')->label('Solicitante')->disabled()->dehydrated(false),
                        TextInput::make('email')->label('E-mail')->disabled()->dehydrated(false),
                        TextInput::make('telefone')->label('Telefone')->disabled()->dehydrated(false),
                        Textarea::make('mensagem')->label('Mensagem')->rows(5)->disabled()->dehydrated(false)->columnSpanFull(),
                    ]),
                Section::make('Resposta')
                    ->description('O solicitante consulta a resposta no portal com o protocolo e o e-mail.')
                    ->columns(2)
                    ->schema([
                        Select::make('status')->label('Situação')->options(StatusPedido::class)->required(),
                        DateTimePicker::make('respondido_em')->label('Respondido em'),
                        Textarea::make('resposta')->label('Resposta ao cidadão')->rows(6)->columnSpanFull(),
                    ]),
            ]);
    }
}
