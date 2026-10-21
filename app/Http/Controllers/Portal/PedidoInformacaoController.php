<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Mail\NovoPedidoInformacao;
use App\Models\PedidoInformacao;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class PedidoInformacaoController extends Controller
{
    public function create(): View
    {
        return view('portal.pedido-informacao');
    }

    public function store(Request $request): RedirectResponse
    {
        // Campo invisível: robôs costumam preenchê-lo.
        if (filled($request->input('site'))) {
            return redirect()->route('pedido.create');
        }

        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'telefone' => ['nullable', 'string', 'max:30'],
            'assunto' => ['required', 'string', 'max:255'],
            'mensagem' => ['required', 'string', 'max:5000'],
            'aceite' => ['accepted'],
        ], [], [
            'aceite' => 'ciência sobre o uso dos dados',
        ]);

        unset($dados['aceite']);
        $pedido = PedidoInformacao::create($dados);

        if ($destino = config('portal.email_pedidos')) {
            try {
                Mail::to($destino)->send(new NovoPedidoInformacao($pedido));
            } catch (Throwable $e) {
                Log::warning('Não foi possível enviar o aviso de pedido de informação.', ['erro' => $e->getMessage()]);
            }
        }

        return redirect()
            ->route('pedido.create')
            ->with('protocolo', $pedido->protocolo);
    }

    public function consultar(Request $request): View
    {
        $pedido = null;

        if ($request->filled(['protocolo', 'email'])) {
            $dados = $request->validate([
                'protocolo' => ['required', 'string', 'max:30'],
                'email' => ['required', 'email', 'max:255'],
            ]);

            $pedido = PedidoInformacao::query()
                ->where('protocolo', strtoupper(trim($dados['protocolo'])))
                ->where('email', $dados['email'])
                ->first();
        }

        return view('portal.pedido-consulta', [
            'pedido' => $pedido,
            'consultou' => $request->filled(['protocolo', 'email']),
        ]);
    }
}
