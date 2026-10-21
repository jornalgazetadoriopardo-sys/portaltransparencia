<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\PerguntaFrequente;
use Illuminate\Contracts\View\View;

class PaginaController extends Controller
{
    public function perguntas(): View
    {
        return view('portal.perguntas', [
            'perguntas' => PerguntaFrequente::query()->where('publicado', true)->orderBy('ordem')->get(),
        ]);
    }

    public function dadosAbertos(): View
    {
        return view('portal.dados-abertos');
    }

    public function acessibilidade(): View
    {
        return view('portal.acessibilidade');
    }

    public function privacidade(): View
    {
        return view('portal.privacidade');
    }
}
