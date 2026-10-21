<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Dados institucionais (registro único).
        Schema::create('entidades', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('sigla')->nullable();
            $table->string('cnpj', 18)->nullable();
            $table->date('data_fundacao')->nullable();
            $table->text('finalidade')->nullable();
            $table->string('endereco')->nullable();
            $table->string('cidade_uf')->nullable();
            $table->string('cep', 9)->nullable();
            $table->string('telefone')->nullable();
            $table->string('email')->nullable();
            $table->string('horario_atendimento')->nullable();
            $table->string('responsavel_transparencia')->nullable();
            $table->string('site')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });

        Schema::create('dirigentes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cargo');
            $table->string('orgao')->default('Diretoria');
            $table->date('mandato_inicio')->nullable();
            $table->date('mandato_fim')->nullable();
            $table->unsignedInteger('ordem')->default(0);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        // Parcerias com o poder público (Lei 13.019/2014, art. 11).
        Schema::create('parcerias', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
            $table->string('numero');
            $table->string('orgao_concedente');
            $table->text('objeto');
            $table->date('data_assinatura');
            $table->date('vigencia_inicio')->nullable();
            $table->date('vigencia_fim')->nullable();
            $table->decimal('valor_total', 14, 2);
            $table->decimal('valor_liberado', 14, 2)->default(0);
            $table->decimal('contrapartida', 14, 2)->default(0);
            $table->string('situacao')->default('em_execucao');
            $table->string('prestacao_situacao')->default('nao_iniciada');
            $table->date('prestacao_data_prevista')->nullable();
            $table->date('prestacao_data_apresentacao')->nullable();
            $table->unsignedSmallInteger('prestacao_prazo_analise_dias')->nullable();
            $table->text('prestacao_resultado')->nullable();
            $table->string('arquivo_instrumento')->nullable();
            $table->string('arquivo_plano_trabalho')->nullable();
            $table->boolean('publicado')->default(true);
            $table->timestamps();
        });

        // Equipe paga com recursos da parceria (art. 11, parágrafo único, VI).
        Schema::create('parceria_equipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parceria_id')->constrained()->cascadeOnDelete();
            $table->string('funcao');
            $table->unsignedSmallInteger('quantidade')->default(1);
            $table->unsignedSmallInteger('carga_horaria_semanal')->nullable();
            $table->decimal('remuneracao_mensal', 12, 2);
            $table->unsignedSmallInteger('exercicio');
            $table->timestamps();
        });

        Schema::create('receitas', function (Blueprint $table) {
            $table->id();
            $table->date('data');
            $table->string('fonte');
            $table->foreignId('parceria_id')->nullable()->constrained()->nullOnDelete();
            $table->string('descricao');
            $table->decimal('valor', 14, 2);
            $table->string('comprovante')->nullable();
            $table->timestamps();
            $table->index('data');
        });

        Schema::create('despesas', function (Blueprint $table) {
            $table->id();
            $table->date('data');
            $table->string('categoria');
            $table->foreignId('parceria_id')->nullable()->constrained()->nullOnDelete();
            $table->string('favorecido');
            $table->string('favorecido_documento', 18)->nullable();
            $table->string('documento_fiscal')->nullable();
            $table->string('descricao');
            $table->decimal('valor', 14, 2);
            $table->string('comprovante')->nullable();
            $table->timestamps();
            $table->index('data');
        });

        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->string('numero');
            $table->string('modalidade');
            $table->foreignId('parceria_id')->nullable()->constrained()->nullOnDelete();
            $table->text('objeto');
            $table->date('data_abertura');
            $table->string('situacao')->default('aberta');
            $table->string('fornecedor')->nullable();
            $table->string('fornecedor_documento', 18)->nullable();
            $table->decimal('valor', 14, 2)->nullable();
            $table->string('arquivo')->nullable();
            $table->timestamps();
        });

        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->string('categoria');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->unsignedSmallInteger('ano')->nullable();
            $table->date('data_publicacao');
            $table->foreignId('parceria_id')->nullable()->constrained()->nullOnDelete();
            $table->string('arquivo');
            $table->boolean('publicado')->default(true);
            $table->timestamps();
            $table->index(['categoria', 'ano']);
        });

        Schema::create('pedidos_informacao', function (Blueprint $table) {
            $table->id();
            $table->string('protocolo')->unique();
            $table->string('nome');
            $table->string('email');
            $table->string('telefone')->nullable();
            $table->string('assunto');
            $table->text('mensagem');
            $table->string('status')->default('recebido');
            $table->text('resposta')->nullable();
            $table->timestamp('respondido_em')->nullable();
            $table->timestamps();
        });

        Schema::create('perguntas_frequentes', function (Blueprint $table) {
            $table->id();
            $table->string('pergunta');
            $table->text('resposta');
            $table->unsignedInteger('ordem')->default(0);
            $table->boolean('publicado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perguntas_frequentes');
        Schema::dropIfExists('pedidos_informacao');
        Schema::dropIfExists('documentos');
        Schema::dropIfExists('compras');
        Schema::dropIfExists('despesas');
        Schema::dropIfExists('receitas');
        Schema::dropIfExists('parceria_equipes');
        Schema::dropIfExists('parcerias');
        Schema::dropIfExists('dirigentes');
        Schema::dropIfExists('entidades');
    }
};
