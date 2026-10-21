<?php

namespace App\Models;

use App\Enums\StatusPedido;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable(['protocolo', 'nome', 'email', 'telefone', 'assunto', 'mensagem', 'status', 'resposta', 'respondido_em'])]
class PedidoInformacao extends Model
{
    protected $table = 'pedidos_informacao';

    protected function casts(): array
    {
        return [
            'status' => StatusPedido::class,
            'respondido_em' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $pedido) {
            $pedido->protocolo ??= static::gerarProtocolo();
        });
    }

    public static function gerarProtocolo(): string
    {
        do {
            $protocolo = now()->format('Y').'-'.strtoupper(Str::random(8));
        } while (static::where('protocolo', $protocolo)->exists());

        return $protocolo;
    }
}
