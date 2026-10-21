<?php

namespace App\Filament\Resources\Parcerias\Schemas;

use App\Enums\SituacaoParceria;
use App\Enums\SituacaoPrestacao;
use App\Enums\TipoParceria;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ParceriaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Instrumento')
                    ->description('Informações exigidas pelo art. 11 da Lei 13.019/2014 (MROSC).')
                    ->columns(3)
                    ->schema([
                        Select::make('tipo')->label('Tipo de instrumento')->options(TipoParceria::class)->required(),
                        TextInput::make('numero')->label('Número')->placeholder('001/2026')->required()->maxLength(255),
                        DatePicker::make('data_assinatura')->label('Data de assinatura')->required(),
                        TextInput::make('orgao_concedente')->label('Órgão / ente concedente')->placeholder('Prefeitura Municipal de ... — Secretaria de ...')->required()->maxLength(255)->columnSpanFull(),
                        Textarea::make('objeto')->label('Descrição do objeto')->rows(3)->required()->columnSpanFull(),
                        DatePicker::make('vigencia_inicio')->label('Vigência — início'),
                        DatePicker::make('vigencia_fim')->label('Vigência — fim'),
                        Select::make('situacao')->label('Situação da parceria')->options(SituacaoParceria::class)->default(SituacaoParceria::EmExecucao)->required(),
                    ]),
                Section::make('Valores')
                    ->columns(3)
                    ->schema([
                        TextInput::make('valor_total')->label('Valor total')->prefix('R$')->numeric()->minValue(0)->required(),
                        TextInput::make('valor_liberado')->label('Valor já liberado')->prefix('R$')->numeric()->minValue(0)->default(0)->required(),
                        TextInput::make('contrapartida')->label('Contrapartida da entidade')->prefix('R$')->numeric()->minValue(0)->default(0),
                    ]),
                Section::make('Prestação de contas')
                    ->columns(2)
                    ->schema([
                        Select::make('prestacao_situacao')->label('Situação')->options(SituacaoPrestacao::class)->default(SituacaoPrestacao::NaoIniciada)->required(),
                        DatePicker::make('prestacao_data_prevista')->label('Data prevista para apresentação'),
                        DatePicker::make('prestacao_data_apresentacao')->label('Data em que foi apresentada'),
                        TextInput::make('prestacao_prazo_analise_dias')->label('Prazo de análise do órgão (dias)')->numeric()->minValue(0),
                        Textarea::make('prestacao_resultado')->label('Resultado da análise / observações')->rows(2)->columnSpanFull(),
                    ]),
                Section::make('Equipe paga com recursos da parceria')
                    ->description('Funções e remuneração prevista (art. 11, VI). Não é necessário informar nomes.')
                    ->collapsible()
                    ->schema([
                        Repeater::make('equipe')
                            ->label('')
                            ->relationship()
                            ->columns(5)
                            ->defaultItems(0)
                            ->addActionLabel('Adicionar função')
                            ->schema([
                                TextInput::make('funcao')->label('Função')->required()->columnSpan(2),
                                TextInput::make('quantidade')->label('Qtde.')->numeric()->minValue(1)->default(1)->required(),
                                TextInput::make('carga_horaria_semanal')->label('Horas/semana')->numeric()->minValue(0),
                                TextInput::make('remuneracao_mensal')->label('Remuneração mensal')->prefix('R$')->numeric()->minValue(0)->required(),
                                TextInput::make('exercicio')->label('Exercício')->numeric()->default(now()->year)->required(),
                            ]),
                    ]),
                Section::make('Arquivos e publicação')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('arquivo_instrumento')->label('Íntegra do instrumento (PDF)')->disk('public')->directory('parcerias')->acceptedFileTypes(['application/pdf'])->maxSize(20480)->downloadable()->openable(),
                        FileUpload::make('arquivo_plano_trabalho')->label('Plano de trabalho (PDF)')->disk('public')->directory('parcerias')->acceptedFileTypes(['application/pdf'])->maxSize(20480)->downloadable()->openable(),
                        Toggle::make('publicado')->label('Publicar no portal')->default(true),
                    ]),
            ]);
    }
}
