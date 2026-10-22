<?php

namespace Tests\Feature;

use App\Models\Entidade;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NomeEntidadeTest extends TestCase
{
    use RefreshDatabase;

    private function rodarMigracao(): void
    {
        (require database_path('migrations/2026_10_22_000001_definir_nome_educandario.php'))->up();
    }

    public function test_troca_o_nome_provisorio(): void
    {
        Entidade::query()->first()->update(['nome' => 'Nome da Associação (edite no painel)']);

        $this->rodarMigracao();

        $this->assertSame('Educandário São José', Entidade::query()->first()->nome);
    }

    public function test_nao_altera_nome_ja_editado(): void
    {
        Entidade::query()->first()->update(['nome' => 'Educandário São José de Rio Pardo']);

        $this->rodarMigracao();

        $this->assertSame('Educandário São José de Rio Pardo', Entidade::query()->first()->nome);
    }

    public function test_nome_aparece_no_titulo_da_pagina(): void
    {
        $this->withoutVite();
        config(['portal.em_construcao' => true]);

        $this->get('/')
            ->assertStatus(503)
            ->assertSee('<title>Em construção — Portal da Transparência — Educandário São José</title>', false);
    }
}
