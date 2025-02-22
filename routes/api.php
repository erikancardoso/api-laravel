<?php

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::domain('{tenant}.api.test')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::get('teste', function () {
            // Captura o parâmetro do subdomínio
            $tenantSlug = request()->route('tenant');

            // Busca o tenant no banco central
            $tenant = Tenant::where('name', $tenantSlug)->first();
            if (!$tenant) {
                return response()->json(['error' => 'Tenant não encontrado'], 404);
            }

            // Atualiza dinamicamente a configuração da conexão 'tenants'
            Config::set('database.connections.tenants.database', $tenant->database);

            // Purga a conexão para garantir que a nova configuração seja aplicada
            DB::purge('tenants');

            // Reconecta explicitamente a conexão 'tenants'
            DB::reconnect('tenants');

            // Verifique se a conexão foi atualizada (opcional)
            $dbName = DB::connection('tenants')->getDatabaseName();
            // dd($dbName);

            // Agora, busque os usuários no banco do tenant
            // Certifique-se que o model User esteja usando a conexão 'tenants'
            $users = User::all();

            return response()->json($users);
        });
    });
});
