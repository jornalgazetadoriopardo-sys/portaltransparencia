<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmConstrucaoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_publico_ve_a_pagina_em_construcao_quando_ligada(): void
    {
        config(['portal.em_construcao' => true]);

        foreach (['/', '/receitas', '/parcerias/exportar'] as $url) {
            $this->get($url)->assertStatus(503)->assertSee('Em construção')->assertDontSee('O que você procura?');
        }

        $this->post(route('pedido.store'), [])->assertStatus(503);
    }

    public function test_painel_continua_acessivel(): void
    {
        config(['portal.em_construcao' => true]);

        $this->get('/admin/login')->assertOk();
    }

    public function test_usuario_logado_ve_o_portal_normal(): void
    {
        config(['portal.em_construcao' => true]);

        $this->actingAs(User::factory()->create())
            ->get('/')
            ->assertOk()
            ->assertSee('O que você procura?');
    }

    public function test_desligado_mostra_o_portal(): void
    {
        config(['portal.em_construcao' => false]);

        $this->get('/')->assertOk()->assertSee('O que você procura?');
    }
}
