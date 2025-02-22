<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class TenantVerifyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $tenantSlug = $request->route('tenant');
        
        $tenant = Tenant::where('name', $tenantSlug)->first();
        
        if (!$tenant) {
            return response()->json(['error' => 'Tenant não encontrado'], 404);
        }

        // Configura o banco de dados do tenant
        Config::set('database.connections.tenants.database', $tenant->database);
        DB::purge('tenants');
        DB::reconnect('tenants');

        // Armazena o tenant na request para uso posterior
        $request->merge(['current_tenant' => $tenant]);

        return $next($request);
    }
} 