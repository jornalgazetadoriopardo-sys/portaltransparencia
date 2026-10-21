<?php

namespace App\Filament\Resources\Documentos\Schemas;

use App\Enums\CategoriaDocumento;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DocumentoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('categoria')->label('Categoria')->options(CategoriaDocumento::class)->required(),
                TextInput::make('titulo')->label('Título')->placeholder('Balanço patrimonial 2025')->required()->maxLength(255),
                TextInput::make('ano')->label('Ano de referência')->numeric()->minValue(1900)->maxValue(2100)->default(now()->year),
                DatePicker::make('data_publicacao')->label('Data de publicação')->default(now())->required(),
                Select::make('parceria_id')->label('Parceria relacionada (opcional)')->relationship('parceria', 'numero')->searchable()->preload(),
                Toggle::make('publicado')->label('Publicar no portal')->default(true)->inline(false),
                Textarea::make('descricao')->label('Descrição (opcional)')->rows(2)->columnSpanFull(),
                FileUpload::make('arquivo')
                    ->label('Arquivo')
                    ->helperText('PDF pesquisável é o formato recomendado. Planilhas (CSV/XLSX) também são aceitas.')
                    ->disk('public')
                    ->directory('documentos')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'text/csv',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.oasis.opendocument.spreadsheet',
                        'application/vnd.oasis.opendocument.text',
                    ])
                    ->maxSize(30720)
                    ->downloadable()
                    ->openable()
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
