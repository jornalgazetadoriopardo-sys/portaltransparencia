<?php

namespace App\Filament\Resources\Entidades\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EntidadeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Identificação')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nome')->label('Razão social')->required()->maxLength(255)->columnSpanFull(),
                        TextInput::make('sigla')->label('Nome fantasia / sigla')->maxLength(255),
                        TextInput::make('cnpj')->label('CNPJ')->mask('99.999.999/9999-99')->maxLength(18),
                        DatePicker::make('data_fundacao')->label('Data de fundação'),
                        TextInput::make('site')->label('Site institucional')->url()->maxLength(255),
                        Textarea::make('finalidade')->label('Finalidade / missão (conforme o estatuto)')->rows(4)->columnSpanFull(),
                        FileUpload::make('logo')->label('Logotipo')->image()->disk('public')->directory('entidade')->maxSize(2048)->columnSpanFull(),
                    ]),
                Section::make('Contato e atendimento ao público')
                    ->columns(2)
                    ->schema([
                        TextInput::make('endereco')->label('Endereço')->maxLength(255)->columnSpanFull(),
                        TextInput::make('cidade_uf')->label('Cidade / UF')->maxLength(255),
                        TextInput::make('cep')->label('CEP')->mask('99999-999'),
                        TextInput::make('telefone')->label('Telefone')->tel()->maxLength(255),
                        TextInput::make('email')->label('E-mail')->email()->maxLength(255),
                        TextInput::make('horario_atendimento')->label('Horário de atendimento')->placeholder('Segunda a sexta, das 8h às 17h')->maxLength(255),
                        TextInput::make('responsavel_transparencia')->label('Responsável pelo portal / pedidos de informação')->maxLength(255),
                    ]),
            ]);
    }
}
