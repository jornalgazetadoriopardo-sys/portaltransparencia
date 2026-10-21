<?php

namespace App\Enums;

use App\Enums\Concerns\TemOpcoes;
use Filament\Support\Contracts\HasLabel;

enum SituacaoParceria: string implements HasLabel
{
    use TemOpcoes;

    case EmExecucao = 'em_execucao';
    case Concluida = 'concluida';
    case Suspensa = 'suspensa';
    case Rescindida = 'rescindida';

    public function getLabel(): string
    {
        return match ($this) {
            self::EmExecucao => 'Em execução',
            self::Concluida => 'Concluída',
            self::Suspensa => 'Suspensa',
            self::Rescindida => 'Rescindida',
        };
    }
}
