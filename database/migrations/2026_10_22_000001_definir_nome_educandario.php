<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Troca o nome provisório criado pelo seeder pelo nome do Educandário São José.
 * Não altera um nome que já tenha sido editado no painel.
 */
return new class extends Migration
{
    private const PROVISORIO = 'Nome da Associação (edite no painel)';

    private const NOME = 'Educandário São José';

    public function up(): void
    {
        $existe = DB::table('entidades')->exists();

        if (! $existe) {
            DB::table('entidades')->insert(['nome' => self::NOME, 'created_at' => now(), 'updated_at' => now()]);

            return;
        }

        DB::table('entidades')
            ->where('nome', self::PROVISORIO)
            ->update(['nome' => self::NOME, 'updated_at' => now()]);
    }

    public function down(): void
    {
        DB::table('entidades')
            ->where('nome', self::NOME)
            ->update(['nome' => self::PROVISORIO, 'updated_at' => now()]);
    }
};
