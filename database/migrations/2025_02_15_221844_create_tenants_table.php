<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Obter o nome do banco atual (da conexão utilizada na migration)
        $currentDatabase = DB::connection()->getDatabaseName();
        $centralDatabase = env('DB_DATABASE', 'api'); // banco central definido no .env

        // Se não estivermos no banco central, não criamos a tabela tenants
        if ($currentDatabase !== $centralDatabase) {
            // Pode até registrar um log ou mensagem, se desejar
            return;
        }

        Schema::create('tenants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('domain')->unique();
            $table->string('database')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $currentDatabase = DB::connection()->getDatabaseName();
        $centralDatabase = env('DB_DATABASE', 'api');

        if ($currentDatabase !== $centralDatabase) {
            return;
        }

        Schema::dropIfExists('tenants');
    }
};
