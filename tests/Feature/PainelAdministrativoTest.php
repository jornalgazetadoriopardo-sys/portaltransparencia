<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DemonstracaoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PainelAdministrativoTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitante_e_enviado_para_o_login(): void
    {
        $this->get('/admin/parcerias')->assertRedirect('/admin/login');
    }

    public function test_telas_do_painel_abrem_para_usuario_logado(): void
    {
        Storage::fake('public');
        $this->seed(DemonstracaoSeeder::class);
        $this->actingAs(User::factory()->create());

        foreach ([
            '/admin', '/admin/entidade', '/admin/entidade/1/edit', '/admin/dirigentes', '/admin/parcerias',
            '/admin/parcerias/create', '/admin/parcerias/1/edit', '/admin/receitas', '/admin/despesas',
            '/admin/compras', '/admin/documentos', '/admin/documentos/create', '/admin/pedidos-informacao',
            '/admin/perguntas-frequentes', '/admin/usuarios',
        ] as $url) {
            $this->get($url)->assertOk();
        }

        // A entidade é registro único: não pode ser criada de novo.
        $this->get('/admin/entidade/create')->assertForbidden();
    }
}
