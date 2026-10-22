<?php

namespace Database\Seeders;

use App\Models\Entidade;
use App\Models\PerguntaFrequente;
use Illuminate\Database\Seeder;

/**
 * Carga inicial para produção: cria o registro da entidade e perguntas frequentes básicas.
 * Rode com: php artisan db:seed --force
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Entidade::query()->firstOrCreate([], [
            'nome' => 'Educandário São José',
        ]);

        $perguntas = [
            ['O que é o Portal da Transparência?', 'É o espaço em que a associação publica, por iniciativa própria, informações sobre sua organização, as parcerias firmadas com o poder público, as receitas, as despesas e as prestações de contas.'],
            ['Por que a associação publica essas informações?', 'Porque recebe recursos públicos. A Lei nº 12.527/2011 (Lei de Acesso à Informação) e a Lei nº 13.019/2014 (Marco Regulatório das Organizações da Sociedade Civil) determinam a divulgação das parcerias e da aplicação desses recursos. Além disso, a transparência é um compromisso da entidade com associados, doadores e a comunidade.'],
            ['Como peço uma informação que não está no portal?', 'Use a página "Pedido de informação". Você recebe um número de protocolo e pode acompanhar a resposta pelo próprio portal. Não é necessário justificar o pedido.'],
            ['Posso usar os dados do portal?', 'Sim. Todas as tabelas podem ser baixadas em CSV (abre no Excel) ou JSON na página "Dados abertos" ou nos botões de cada seção.'],
        ];

        foreach ($perguntas as $ordem => [$pergunta, $resposta]) {
            PerguntaFrequente::query()->firstOrCreate(['pergunta' => $pergunta], ['resposta' => $resposta, 'ordem' => $ordem]);
        }
    }
}
