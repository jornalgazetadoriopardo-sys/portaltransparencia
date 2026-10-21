<?php

namespace Tests\Feature;

use App\Enums\StatusPedido;
use App\Models\PedidoInformacao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PedidoInformacaoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    private function dadosValidos(array $extra = []): array
    {
        return array_merge([
            'nome' => 'Fulana de Tal',
            'email' => 'fulana@example.com',
            'assunto' => 'Contrato de aluguel',
            'mensagem' => 'Gostaria de receber cópia do contrato de aluguel da sede.',
            'aceite' => '1',
        ], $extra);
    }

    public function test_cidadao_registra_pedido_e_recebe_protocolo(): void
    {
        $resposta = $this->post(route('pedido.store'), $this->dadosValidos());

        $pedido = PedidoInformacao::query()->firstOrFail();
        $resposta->assertRedirect(route('pedido.create'))->assertSessionHas('protocolo', $pedido->protocolo);
        $this->assertSame(StatusPedido::Recebido, $pedido->fresh()->status);
        $this->assertMatchesRegularExpression('/^\d{4}-[A-Z0-9]{8}$/', $pedido->protocolo);

        $this->followRedirects($resposta)->assertSee($pedido->protocolo);
    }

    public function test_campos_obrigatorios_e_aceite(): void
    {
        $this->post(route('pedido.store'), ['nome' => ''])
            ->assertSessionHasErrors(['nome', 'email', 'assunto', 'mensagem', 'aceite']);

        $this->assertDatabaseCount('pedidos_informacao', 0);
    }

    public function test_robo_que_preenche_campo_oculto_e_ignorado(): void
    {
        $this->post(route('pedido.store'), $this->dadosValidos(['site' => 'http://spam.example']))
            ->assertRedirect(route('pedido.create'));

        $this->assertDatabaseCount('pedidos_informacao', 0);
    }

    public function test_consulta_exige_protocolo_e_email_corretos(): void
    {
        $pedido = PedidoInformacao::query()->create(collect($this->dadosValidos())->except('aceite')->all());
        $pedido->update(['status' => StatusPedido::Respondido, 'resposta' => 'Segue o contrato em anexo.', 'respondido_em' => now()]);

        $this->get(route('pedido.consultar', ['protocolo' => strtolower($pedido->protocolo), 'email' => 'fulana@example.com']))
            ->assertOk()
            ->assertSee('Segue o contrato em anexo.');

        $this->get(route('pedido.consultar', ['protocolo' => $pedido->protocolo, 'email' => 'outra@example.com']))
            ->assertOk()
            ->assertSee('Nenhum pedido encontrado')
            ->assertDontSee('Segue o contrato em anexo.');
    }
}
