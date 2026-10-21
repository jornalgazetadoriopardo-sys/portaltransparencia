<?php

namespace App\Enums;

use App\Enums\Concerns\TemOpcoes;
use Filament\Support\Contracts\HasLabel;

enum CategoriaDocumento: string implements HasLabel
{
    use TemOpcoes;

    case Estatuto = 'estatuto';
    case Ata = 'ata';
    case Balanco = 'balanco';
    case ParecerConselho = 'parecer_conselho';
    case Auditoria = 'auditoria';
    case RelatorioAtividades = 'relatorio_atividades';
    case PrestacaoContas = 'prestacao_contas';
    case PlanoTrabalho = 'plano_trabalho';
    case Regulamento = 'regulamento';
    case Certidao = 'certidao';
    case Outro = 'outro';

    public function getLabel(): string
    {
        return match ($this) {
            self::Estatuto => 'Estatuto social',
            self::Ata => 'Atas de assembleias e reuniões',
            self::Balanco => 'Balanço patrimonial e demonstrações contábeis',
            self::ParecerConselho => 'Parecer do Conselho Fiscal',
            self::Auditoria => 'Relatório de auditoria',
            self::RelatorioAtividades => 'Relatório de atividades',
            self::PrestacaoContas => 'Prestação de contas de parcerias',
            self::PlanoTrabalho => 'Planos de trabalho',
            self::Regulamento => 'Regulamentos e políticas internas',
            self::Certidao => 'Certidões e títulos',
            self::Outro => 'Outros documentos',
        };
    }
}
