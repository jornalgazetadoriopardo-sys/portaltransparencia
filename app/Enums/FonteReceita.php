<?php

namespace App\Enums;

use App\Enums\Concerns\TemOpcoes;
use Filament\Support\Contracts\HasLabel;

enum FonteReceita: string implements HasLabel
{
    use TemOpcoes;

    case RecursosPublicos = 'recursos_publicos';
    case EmendaParlamentar = 'emenda_parlamentar';
    case DoacoesPf = 'doacoes_pf';
    case DoacoesPj = 'doacoes_pj';
    case ContribuicoesAssociados = 'contribuicoes_associados';
    case Eventos = 'eventos';
    case Servicos = 'servicos';
    case Rendimentos = 'rendimentos';
    case Outras = 'outras';

    public function getLabel(): string
    {
        return match ($this) {
            self::RecursosPublicos => 'Recursos públicos (parcerias)',
            self::EmendaParlamentar => 'Emenda parlamentar',
            self::DoacoesPf => 'Doações de pessoas físicas',
            self::DoacoesPj => 'Doações de empresas',
            self::ContribuicoesAssociados => 'Contribuições de associados',
            self::Eventos => 'Eventos e campanhas',
            self::Servicos => 'Prestação de serviços',
            self::Rendimentos => 'Rendimentos financeiros',
            self::Outras => 'Outras receitas',
        };
    }
}
