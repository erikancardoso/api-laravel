<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class TenantCreateCommand extends Command
{
    protected $signature = 'tenant:create';
    protected $description = 'Cria um novo tenant e executa as migrations no banco dele';

    public function handle()
    {
        $name   = $this->ask('What is the Tenant Name?');
        $domain = $this->ask('What is the Tenant Domain?');
        // Aqui extraímos o nome do banco a partir do domínio (pode ajustar conforme sua necessidade)
        $database = explode('.', $domain)[0];
// Verifica se o banco de dados do tenant já existe
        $exists = DB::select('SELECT schema_name FROM information_schema.schemata WHERE schema_name = ?', [$database]);
        if (!empty($exists)) {
            $this->error("O banco de dados '$database' já existe.");
            return;
        }
        // Verifica se o tenant já existe no banco central
        if (Tenant::where('domain', $domain)->exists()) {
            $this->error("O tenant com domínio '{$domain}' já existe.");
            return;
        }



        // Cria o banco de dados do tenant
        DB::statement('CREATE DATABASE IF NOT EXISTS `'.$database.'`');
        $this->info("Banco de dados '{$database}' criado com sucesso!");

        // Atualiza dinamicamente a configuração da conexão 'tenants' para usar o novo banco
        config(['database.connections.tenants.database' => $database]);

        // Executa as migrations para o tenant (assegure-se que as migrations específicas do tenant estejam no path indicado)
        $this->info("Executando as migrations para o tenant...");
        Artisan::call('migrate', [
            '--database' => 'tenants',
            '--path'     => 'database/migrations', // ou 'database/migrations/tenant' se você organizá-las assim
            '--force'    => true,
        ]);

        $this->info(Artisan::output());

        // Registra o tenant no banco central
        Tenant::create([
            'id'       => (string) Str::uuid(),  // se estiver usando ulid/uuid manualmente
            'name'     => $name,
            'domain'   => $domain,
            'database' => $database,
        ]);

        $this->info("Tenant criado com sucesso!");
    }
}
