<?php

namespace App\Enums;

use App\Enums\Concerns\TemOpcoes;
use Filament\Support\Contracts\HasLabel;

enum TipoParceria: string implements HasLabel
{
    use TemOpcoes;

    case TermoColaboracao = 'termo_colaboracao';
    case TermoFomento = 'termo_fomento';
    case AcordoCooperacao = 'acordo_cooperacao';
    case Convenio = 'convenio';
    case TermoParceria = 'termo_parceria';
    case ContratoGestao = 'contrato_gestao';
    case Subvencao = 'subvencao';
    case Outro = 'outro';

    public function getLabel(): string
    {
        return match ($this) {
            self::TermoColaboracao => 'Termo de Colaboração',
            self::TermoFomento => 'Termo de Fomento',
            self::AcordoCooperacao => 'Acordo de Cooperação',
            self::Convenio => 'Convênio',
            self::TermoParceria => 'Termo de Parceria (OSCIP)',
            self::ContratoGestao => 'Contrato de Gestão',
            self::Subvencao => 'Subvenção Social',
            self::Outro => 'Outro instrumento',
        };
    }
}
