<?php

namespace App\Support;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Exporta consultas do portal em CSV (abre direto no Excel/LibreOffice) ou JSON (dados abertos).
 */
class ExportadorCsv
{
    /**
     * @param  array<string, string>  $colunas  chave => cabeçalho
     * @param  iterable<mixed>  $registros
     * @param  Closure(mixed): array<string, mixed>  $linha  converte um registro em [chave => valor]
     */
    public static function responder(Request $request, string $nome, array $colunas, iterable $registros, Closure $linha): StreamedResponse|JsonResponse
    {
        $arquivo = $nome.'-'.now()->format('Y-m-d');

        if ($request->query('formato') === 'json') {
            $dados = [];
            foreach ($registros as $registro) {
                $dados[] = $linha($registro);
            }

            return response()->json([
                'gerado_em' => now()->toIso8601String(),
                'total' => count($dados),
                'dados' => $dados,
            ], 200, [
                'Content-Disposition' => 'attachment; filename="'.$arquivo.'.json"',
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }

        return response()->streamDownload(function () use ($colunas, $registros, $linha) {
            $saida = fopen('php://output', 'w');
            // BOM para o Excel reconhecer UTF-8 (acentos).
            fwrite($saida, "\xEF\xBB\xBF");
            fputcsv($saida, array_values($colunas), ';', '"', '');

            foreach ($registros as $registro) {
                $valores = $linha($registro);
                fputcsv($saida, array_map(
                    fn (string $chave) => self::valorCsv($valores[$chave] ?? null),
                    array_keys($colunas),
                ), ';', '"', '');
            }

            fclose($saida);
        }, $arquivo.'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private static function valorCsv(mixed $valor): string
    {
        if (is_float($valor)) {
            return number_format($valor, 2, ',', '');
        }

        return (string) $valor;
    }
}
