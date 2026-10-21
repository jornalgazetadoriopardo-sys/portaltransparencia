<?php

namespace App\Enums;

use App\Enums\Concerns\TemOpcoes;
use Filament\Support\Contracts\HasLabel;

enum CategoriaDespesa: string implements HasLabel
{
    use TemOpcoes;

    case Pessoal = 'pessoal';
    case Encargos = 'encargos';
    case ServicosTerceiros = 'servicos_terceiros';
    case MaterialConsumo = 'material_consumo';
    case Equipamentos = 'equipamentos';
    case Aluguel = 'aluguel';
    case Utilidades = 'utilidades';
    case Transporte = 'transporte';
    case Alimentacao = 'alimentacao';
    case Tarifas = 'tarifas';
    case Impostos = 'impostos';
    case Outras = 'outras';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pessoal => 'Pessoal (salários)',
            self::Encargos => 'Encargos sociais e trabalhistas',
            self::ServicosTerceiros => 'Serviços de terceiros',
            self::MaterialConsumo => 'Material de consumo',
            self::Equipamentos => 'Equipamentos e material permanente',
            self::Aluguel => 'Aluguel e condomínio',
            self::Utilidades => 'Água, energia, telefone e internet',
            self::Transporte => 'Transporte e combustível',
            self::Alimentacao => 'Alimentação',
            self::Tarifas => 'Tarifas bancárias',
            self::Impostos => 'Impostos e taxas',
            self::Outras => 'Outras despesas',
        };
    }
}
