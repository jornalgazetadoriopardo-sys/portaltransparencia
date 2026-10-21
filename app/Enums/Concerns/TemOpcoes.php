<?php

namespace App\Enums\Concerns;

trait TemOpcoes
{
    /**
     * Lista valor => rótulo, usada nos filtros públicos.
     *
     * @return array<string, string>
     */
    public static function opcoes(): array
    {
        $opcoes = [];

        foreach (self::cases() as $case) {
            $opcoes[$case->value] = $case->getLabel();
        }

        return $opcoes;
    }
}
