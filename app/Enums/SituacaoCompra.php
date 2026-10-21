<?php

namespace App\Enums;

use App\Enums\Concerns\TemOpcoes;
use Filament\Support\Contracts\HasLabel;

enum SituacaoCompra: string implements HasLabel
{
    use TemOpcoes;

    case Aberta = 'aberta';
    case EmAnalise = 'em_analise';
    case Concluida = 'concluida';
    case Cancelada = 'cancelada';
    case Deserta = 'deserta';

    public function getLabel(): string
    {
        return match ($this) {
            self::Aberta => 'Aberta',
            self::EmAnalise => 'Em análise',
            self::Concluida => 'Concluída',
            self::Cancelada => 'Cancelada',
            self::Deserta => 'Deserta / fracassada',
        };
    }
}
