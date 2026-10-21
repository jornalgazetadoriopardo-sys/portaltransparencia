<?php

namespace App\Mail;

use App\Models\PedidoInformacao;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class NovoPedidoInformacao extends Mailable
{
    public function __construct(public PedidoInformacao $pedido) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Novo pedido de informação — protocolo '.$this->pedido->protocolo,
        );
    }

    public function content(): Content
    {
        return new Content(text: 'mail.novo-pedido');
    }
}
