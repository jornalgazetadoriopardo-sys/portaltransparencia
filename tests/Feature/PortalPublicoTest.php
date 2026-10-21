<?php

namespace Tests\Feature;

use App\Models\Despesa;
use App\Models\Parceria;
use Database\Seeders\DemonstracaoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PortalPublicoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        Storage::fake('public');
        $this->seed(DemonstracaoSeeder::class);
    }

    public static function paginas(): array
    {
        return [
            ['/'], ['/institucional'], ['/parcerias'], ['/receitas'], ['/despesas'], ['/compras'],
            ['/prestacao-de-contas'], ['/documentos'], ['/pedido-de-informacao'], ['/pedido-de-informacao/consulta'],
            ['/perguntas-frequentes'], ['/dados-abertos'], ['/acessibilidade'], ['/privacidade'], ['/busca?q=cozinha'],
        ];
    }

    #[DataProvider('paginas')]
    public function test_paginas_publicas_abrem(string $url): void
    {
        $this->get($url)->assertOk()->assertSee('Associação Exemplo de Assistência Social');
    }

    public function test_pagina_da_parceria_mostra_informacoes_do_art_11(): void
    {
        $parceria = Parceria::query()->where('numero', 'like', '001/%')->firstOrFail();

        $this->get(route('parcerias.show', $parceria))
            ->assertOk()
            ->assertSee($parceria->orgao_concedente)
            ->assertSee('12.345.678/0001-90')
            ->assertSee('R$ 240.000,00')
            ->assertSee('Educador(a) social')
            ->assertSee('Em elaboração');
    }

    public function test_parceria_nao_publicada_nao_aparece(): void
    {
        $parceria = Parceria::query()->firstOrFail();
        $parceria->update(['publicado' => false]);

        $this->get(route('parcerias.show', $parceria))->assertNotFound();
        $this->get(route('parcerias.index'))->assertDontSee('Nº '.$parceria->numero.' —', false);
    }

    public function test_filtros_restringem_as_despesas(): void
    {
        $this->get(route('despesas.index', ['categoria' => 'equipamentos']))
            ->assertOk()
            ->assertSee('Fogão industrial')
            ->assertDontSee('Energia elétrica da sede');
    }

    public function test_filtro_invalido_e_rejeitado(): void
    {
        $this->get('/despesas?categoria=nao-existe')->assertRedirect();
    }

    public function test_exporta_csv_com_bom_e_cpf_mascarado(): void
    {
        $resposta = $this->get(route('despesas.exportar'));

        $resposta->assertOk();
        $this->assertStringContainsString('text/csv', $resposta->headers->get('Content-Type'));

        $csv = $resposta->streamedContent();
        $this->assertStringStartsWith("\xEF\xBB\xBF", $csv);
        $this->assertStringContainsString('Data;Categoria;Favorecido', $csv);
        $this->assertStringContainsString('***.456.789-**', $csv);
        $this->assertStringNotContainsString('12345678901', $csv);
        $this->assertStringContainsString('22.333.444/0001-55', $csv);
    }

    public function test_exporta_json(): void
    {
        $total = Despesa::query()->count();

        $this->get(route('despesas.exportar', ['formato' => 'json']))
            ->assertOk()
            ->assertJsonPath('total', $total)
            ->assertJsonStructure(['gerado_em', 'total', 'dados' => [['data', 'categoria', 'favorecido', 'valor']]]);
    }

    public function test_busca_exige_tres_letras(): void
    {
        $this->get('/busca?q=co')->assertOk()->assertSee('Digite pelo menos 3 letras');
    }
}
