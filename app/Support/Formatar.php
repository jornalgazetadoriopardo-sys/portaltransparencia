<?php

namespace App\Support;

use Carbon\CarbonInterface;

class Formatar
{
    public static function moeda(float|string|null $valor): string
    {
        return 'R$ '.number_format((float) $valor, 2, ',', '.');
    }

    public static function data(?CarbonInterface $data): string
    {
        return $data?->format('d/m/Y') ?? '—';
    }

    /**
     * CNPJ aparece completo; CPF é mascarado (***.456.789-**), como recomenda a LGPD.
     */
    public static function documentoPublico(?string $documento): ?string
    {
        if (blank($documento)) {
            return null;
        }

        $digitos = preg_replace('/\D/', '', $documento);

        if (strlen($digitos) === 14) {
            return vsprintf('%s.%s.%s/%s-%s', [
                substr($digitos, 0, 2), substr($digitos, 2, 3), substr($digitos, 5, 3),
                substr($digitos, 8, 4), substr($digitos, 12, 2),
            ]);
        }

        if (strlen($digitos) === 11) {
            return '***.'.substr($digitos, 3, 3).'.'.substr($digitos, 6, 3).'-**';
        }

        return null;
    }
}
