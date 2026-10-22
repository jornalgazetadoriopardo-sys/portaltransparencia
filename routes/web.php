<?php

use App\Http\Controllers\Portal\BuscaController;
use App\Http\Controllers\Portal\CompraController;
use App\Http\Controllers\Portal\DespesaController;
use App\Http\Controllers\Portal\DocumentoController;
use App\Http\Controllers\Portal\InicioController;
use App\Http\Controllers\Portal\InstitucionalController;
use App\Http\Controllers\Portal\PaginaController;
use App\Http\Controllers\Portal\ParceriaController;
use App\Http\Controllers\Portal\PedidoInformacaoController;
use App\Http\Controllers\Portal\PrestacaoContasController;
use App\Http\Controllers\Portal\ReceitaController;
use Illuminate\Support\Facades\Route;

Route::middleware('em-construcao')->group(function () {
    Route::get('/', InicioController::class)->name('inicio');
    Route::get('/institucional', InstitucionalController::class)->name('institucional');

    Route::get('/parcerias', [ParceriaController::class, 'index'])->name('parcerias.index');
    Route::get('/parcerias/exportar', [ParceriaController::class, 'exportar'])->name('parcerias.exportar');
    Route::get('/parcerias/{parceria}', [ParceriaController::class, 'show'])->name('parcerias.show');

    Route::get('/receitas', [ReceitaController::class, 'index'])->name('receitas.index');
    Route::get('/receitas/exportar', [ReceitaController::class, 'exportar'])->name('receitas.exportar');
    Route::get('/despesas', [DespesaController::class, 'index'])->name('despesas.index');
    Route::get('/despesas/exportar', [DespesaController::class, 'exportar'])->name('despesas.exportar');
    Route::get('/compras', [CompraController::class, 'index'])->name('compras.index');
    Route::get('/compras/exportar', [CompraController::class, 'exportar'])->name('compras.exportar');

    Route::get('/prestacao-de-contas', PrestacaoContasController::class)->name('prestacao-contas');
    Route::get('/documentos', [DocumentoController::class, 'index'])->name('documentos.index');
    Route::get('/documentos/exportar', [DocumentoController::class, 'exportar'])->name('documentos.exportar');

    Route::get('/pedido-de-informacao', [PedidoInformacaoController::class, 'create'])->name('pedido.create');
    Route::post('/pedido-de-informacao', [PedidoInformacaoController::class, 'store'])
        ->middleware('throttle:5,10')
        ->name('pedido.store');
    Route::get('/pedido-de-informacao/consulta', [PedidoInformacaoController::class, 'consultar'])
        ->middleware('throttle:20,1')
        ->name('pedido.consultar');

    Route::get('/perguntas-frequentes', [PaginaController::class, 'perguntas'])->name('perguntas');
    Route::get('/dados-abertos', [PaginaController::class, 'dadosAbertos'])->name('dados-abertos');
    Route::get('/acessibilidade', [PaginaController::class, 'acessibilidade'])->name('acessibilidade');
    Route::get('/privacidade', [PaginaController::class, 'privacidade'])->name('privacidade');
    Route::get('/busca', BuscaController::class)->name('busca');
});
