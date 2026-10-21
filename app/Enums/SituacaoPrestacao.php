<?php

namespace App\Enums;

use App\Enums\Concerns\TemOpcoes;
use Filament\Support\Contracts\HasLabel;

enum SituacaoPrestacao: string implements HasLabel
{
    use TemOpcoes;

    case NaoIniciada = 'nao_iniciada';
    case EmElaboracao = 'em_elaboracao';
    case Apresentada = 'apresentada';
    case EmAnalise = 'em_analise';
    case Aprovada = 'aprovada';
    case AprovadaRessalvas = 'aprovada_ressalvas';
    case Rejeitada = 'rejeitada';

    public function getLabel(): string
    {
        return match ($this) {
            self::NaoIniciada => 'Ainda não exigível',
            self::EmElaboracao => 'Em elaboração',
            self::Apresentada => 'Apresentada',
            self::EmAnalise => 'Em análise pelo órgão',
            self::Aprovada => 'Aprovada',
            self::AprovadaRessalvas => 'Aprovada com ressalvas',
            self::Rejeitada => 'Rejeitada',
        };
    }
}
