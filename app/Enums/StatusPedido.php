<?php

namespace App\Enums;

use App\Enums\Concerns\TemOpcoes;
use Filament\Support\Contracts\HasLabel;

enum StatusPedido: string implements HasLabel
{
    use TemOpcoes;

    case Recebido = 'recebido';
    case EmAnalise = 'em_analise';
    case Respondido = 'respondido';
    case Indeferido = 'indeferido';

    public function getLabel(): string
    {
        return match ($this) {
            self::Recebido => 'Recebido',
            self::EmAnalise => 'Em análise',
            self::Respondido => 'Respondido',
            self::Indeferido => 'Indeferido',
        };
    }
}
