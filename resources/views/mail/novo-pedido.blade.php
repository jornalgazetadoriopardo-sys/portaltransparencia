Um novo pedido de informação foi registrado no Portal da Transparência.

Protocolo: {{ $pedido->protocolo }}
Solicitante: {{ $pedido->nome }} <{{ $pedido->email }}>
Assunto: {{ $pedido->assunto }}

{{ $pedido->mensagem }}

Responda pelo painel: {{ url('/admin/pedidos-informacao/'.$pedido->id.'/edit') }}
