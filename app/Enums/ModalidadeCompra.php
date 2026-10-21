<?php

namespace App\Enums;

use App\Enums\Concerns\TemOpcoes;
use Filament\Support\Contracts\HasLabel;

enum ModalidadeCompra: string implements HasLabel
{
    use TemOpcoes;

    case CotacaoPrevia = 'cotacao_previa';
    case Dispensa = 'dispensa';
    case Inexigibilidade = 'inexigibilidade';
    case Concorrencia = 'concorrencia';
    case CompraDireta = 'compra_direta';

    public function getLabel(): string
    {
        return match ($this) {
            self::CotacaoPrevia => 'Cotação prévia de preços',
            self::Dispensa => 'Dispensa de cotação',
            self::Inexigibilidade => 'Inexigibilidade',
            self::Concorrencia => 'Seleção de fornecedores (edital)',
            self::CompraDireta => 'Compra direta (pequeno valor)',
        };
    }
}
