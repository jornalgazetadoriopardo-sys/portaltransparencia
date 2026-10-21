<?php

namespace Database\Seeders;

use App\Enums\CategoriaDespesa;
use App\Enums\CategoriaDocumento;
use App\Enums\FonteReceita;
use App\Enums\ModalidadeCompra;
use App\Enums\SituacaoCompra;
use App\Enums\SituacaoParceria;
use App\Enums\SituacaoPrestacao;
use App\Enums\TipoParceria;
use App\Models\Compra;
use App\Models\Despesa;
use App\Models\Dirigente;
use App\Models\Documento;
use App\Models\Entidade;
use App\Models\Parceria;
use App\Models\Receita;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Dados fictícios para conhecer o portal. NÃO use em produção.
 * Rode com: php artisan db:seed --class=DemonstracaoSeeder
 */
class DemonstracaoSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(DatabaseSeeder::class);

        Entidade::query()->first()->update([
            'nome' => 'Associação Exemplo de Assistência Social',
            'sigla' => 'AEAS',
            'cnpj' => '12.345.678/0001-90',
            'data_fundacao' => '1998-03-15',
            'finalidade' => 'Promover a assistência social, a educação complementar e a inclusão de crianças, adolescentes e famílias em situação de vulnerabilidade.',
            'endereco' => 'Rua das Flores, 100 — Centro',
            'cidade_uf' => 'Rio Pardo / RS',
            'cep' => '96640-000',
            'telefone' => '(51) 3000-0000',
            'email' => 'contato@associacaoexemplo.org.br',
            'horario_atendimento' => 'Segunda a sexta, das 8h às 17h',
            'responsavel_transparencia' => 'Secretaria administrativa',
        ]);

        foreach ([
            ['Maria da Silva', 'Presidente', 'Diretoria', 1],
            ['João Pereira', 'Vice-presidente', 'Diretoria', 2],
            ['Ana Souza', 'Tesoureira', 'Diretoria', 3],
            ['Carlos Lima', 'Secretário', 'Diretoria', 4],
            ['Beatriz Rocha', 'Conselheira', 'Conselho Fiscal', 1],
            ['Paulo Mendes', 'Conselheiro', 'Conselho Fiscal', 2],
        ] as [$nome, $cargo, $orgao, $ordem]) {
            Dirigente::query()->create([
                'nome' => $nome, 'cargo' => $cargo, 'orgao' => $orgao, 'ordem' => $ordem,
                'mandato_inicio' => '2025-01-01', 'mandato_fim' => '2027-12-31',
            ]);
        }

        $ano = now()->year;

        $p1 = Parceria::query()->create([
            'tipo' => TipoParceria::TermoColaboracao, 'numero' => '001/'.$ano,
            'orgao_concedente' => 'Prefeitura Municipal — Secretaria de Assistência Social',
            'objeto' => 'Execução do Serviço de Convivência e Fortalecimento de Vínculos para 120 crianças e adolescentes de 6 a 15 anos.',
            'data_assinatura' => $ano.'-02-01', 'vigencia_inicio' => $ano.'-02-01', 'vigencia_fim' => $ano.'-12-31',
            'valor_total' => 240000, 'valor_liberado' => 160000, 'situacao' => SituacaoParceria::EmExecucao,
            'prestacao_situacao' => SituacaoPrestacao::EmElaboracao, 'prestacao_data_prevista' => ($ano + 1).'-02-28',
            'prestacao_prazo_analise_dias' => 150,
        ]);
        $p1->equipe()->createMany([
            ['funcao' => 'Coordenador(a) pedagógico(a)', 'quantidade' => 1, 'carga_horaria_semanal' => 40, 'remuneracao_mensal' => 4200, 'exercicio' => $ano],
            ['funcao' => 'Educador(a) social', 'quantidade' => 4, 'carga_horaria_semanal' => 40, 'remuneracao_mensal' => 2400, 'exercicio' => $ano],
            ['funcao' => 'Assistente social', 'quantidade' => 1, 'carga_horaria_semanal' => 30, 'remuneracao_mensal' => 3900, 'exercicio' => $ano],
        ]);

        $p2 = Parceria::query()->create([
            'tipo' => TipoParceria::TermoFomento, 'numero' => '014/'.($ano - 1),
            'orgao_concedente' => 'Governo do Estado — Secretaria de Desenvolvimento Social',
            'objeto' => 'Aquisição de equipamentos e reforma da cozinha comunitária.',
            'data_assinatura' => ($ano - 1).'-06-10', 'vigencia_inicio' => ($ano - 1).'-06-10', 'vigencia_fim' => ($ano - 1).'-12-20',
            'valor_total' => 85000, 'valor_liberado' => 85000, 'contrapartida' => 5000, 'situacao' => SituacaoParceria::Concluida,
            'prestacao_situacao' => SituacaoPrestacao::Aprovada, 'prestacao_data_prevista' => $ano.'-02-20',
            'prestacao_data_apresentacao' => $ano.'-02-12', 'prestacao_prazo_analise_dias' => 90,
            'prestacao_resultado' => 'Contas aprovadas sem ressalvas.',
        ]);

        foreach ([[2, 80000], [5, 80000]] as [$mes, $valor]) {
            Receita::query()->create(['data' => sprintf('%d-%02d-05', $ano, $mes), 'fonte' => FonteReceita::RecursosPublicos, 'parceria_id' => $p1->id, 'descricao' => 'Parcela do Termo de Colaboração 001/'.$ano, 'valor' => $valor]);
        }
        Receita::query()->create(['data' => ($ano - 1).'-07-01', 'fonte' => FonteReceita::RecursosPublicos, 'parceria_id' => $p2->id, 'descricao' => 'Parcela única do Termo de Fomento 014/'.($ano - 1), 'valor' => 85000]);
        for ($mes = 1; $mes <= min(now()->month, 9); $mes++) {
            Receita::query()->create(['data' => sprintf('%d-%02d-10', $ano, $mes), 'fonte' => FonteReceita::ContribuicoesAssociados, 'descricao' => 'Contribuições de associados', 'valor' => 3200 + $mes * 50]);
            Receita::query()->create(['data' => sprintf('%d-%02d-15', $ano, $mes), 'fonte' => FonteReceita::DoacoesPf, 'descricao' => 'Doações de pessoas físicas (campanha mensal)', 'valor' => 1800 + $mes * 30]);
        }
        Receita::query()->create(['data' => $ano.'-06-20', 'fonte' => FonteReceita::Eventos, 'descricao' => 'Festa junina beneficente', 'valor' => 12500]);

        for ($mes = 2; $mes <= min(now()->month, 9); $mes++) {
            Despesa::query()->create(['data' => sprintf('%d-%02d-05', $ano, $mes), 'categoria' => CategoriaDespesa::Pessoal, 'parceria_id' => $p1->id, 'favorecido' => 'Folha de pagamento — equipe do projeto', 'descricao' => 'Salários da equipe do SCFV', 'valor' => 17700]);
            Despesa::query()->create(['data' => sprintf('%d-%02d-20', $ano, $mes), 'categoria' => CategoriaDespesa::Encargos, 'parceria_id' => $p1->id, 'favorecido' => 'Receita Federal / FGTS', 'descricao' => 'INSS e FGTS da equipe', 'valor' => 5900]);
            Despesa::query()->create(['data' => sprintf('%d-%02d-12', $ano, $mes), 'categoria' => CategoriaDespesa::Utilidades, 'favorecido' => 'Companhia de Energia S.A.', 'favorecido_documento' => '11222333000144', 'documento_fiscal' => 'Fatura '.$mes, 'descricao' => 'Energia elétrica da sede', 'valor' => 640 + $mes * 12]);
        }
        Despesa::query()->create(['data' => $ano.'-03-18', 'categoria' => CategoriaDespesa::Alimentacao, 'parceria_id' => $p1->id, 'favorecido' => 'Mercado Bom Preço Ltda.', 'favorecido_documento' => '22333444000155', 'documento_fiscal' => 'NF 4512', 'descricao' => 'Gêneros alimentícios para lanches', 'valor' => 6350.40]);
        Despesa::query()->create(['data' => $ano.'-04-02', 'categoria' => CategoriaDespesa::ServicosTerceiros, 'favorecido' => 'Roberto Alves', 'favorecido_documento' => '12345678901', 'documento_fiscal' => 'RPA 12', 'descricao' => 'Oficina de música (autônomo)', 'valor' => 1500]);
        Despesa::query()->create(['data' => ($ano - 1).'-09-15', 'categoria' => CategoriaDespesa::Equipamentos, 'parceria_id' => $p2->id, 'favorecido' => 'Equipamentos Industriais Sul Ltda.', 'favorecido_documento' => '33444555000166', 'documento_fiscal' => 'NF 889', 'descricao' => 'Fogão industrial, coifa e freezer', 'valor' => 42800]);

        Compra::query()->create(['numero' => 'COT-003/'.$ano, 'modalidade' => ModalidadeCompra::CotacaoPrevia, 'parceria_id' => $p1->id, 'objeto' => 'Fornecimento de gêneros alimentícios para o 1º semestre', 'data_abertura' => $ano.'-02-20', 'situacao' => SituacaoCompra::Concluida, 'fornecedor' => 'Mercado Bom Preço Ltda.', 'fornecedor_documento' => '22333444000155', 'valor' => 31000]);
        Compra::query()->create(['numero' => 'COT-007/'.$ano, 'modalidade' => ModalidadeCompra::CotacaoPrevia, 'objeto' => 'Material pedagógico para oficinas', 'data_abertura' => now()->subDays(5)->toDateString(), 'situacao' => SituacaoCompra::Aberta]);

        Storage::disk('public')->put('documentos/exemplo.pdf', "%PDF-1.4\n% Documento de demonstração\n");
        foreach ([
            [CategoriaDocumento::Estatuto, 'Estatuto social consolidado', null],
            [CategoriaDocumento::Regulamento, 'Regulamento de compras e contratações', null],
            [CategoriaDocumento::Certidao, 'Certidão negativa de débitos federais', $ano],
            [CategoriaDocumento::Balanco, 'Balanço patrimonial e DRE '.($ano - 1), $ano - 1],
            [CategoriaDocumento::ParecerConselho, 'Parecer do Conselho Fiscal '.($ano - 1), $ano - 1],
            [CategoriaDocumento::RelatorioAtividades, 'Relatório anual de atividades '.($ano - 1), $ano - 1],
            [CategoriaDocumento::Ata, 'Ata da assembleia de aprovação de contas '.($ano - 1), $ano - 1],
        ] as [$categoria, $titulo, $ref]) {
            Documento::query()->create(['categoria' => $categoria, 'titulo' => $titulo, 'ano' => $ref, 'data_publicacao' => now()->subDays(rand(5, 200)), 'arquivo' => 'documentos/exemplo.pdf']);
        }
        Documento::query()->create(['categoria' => CategoriaDocumento::PlanoTrabalho, 'titulo' => 'Plano de trabalho — Termo de Colaboração 001/'.$ano, 'ano' => $ano, 'data_publicacao' => $ano.'-02-01', 'parceria_id' => $p1->id, 'arquivo' => 'documentos/exemplo.pdf']);
    }
}
