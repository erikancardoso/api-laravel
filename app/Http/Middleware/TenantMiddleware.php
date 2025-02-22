<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $tenant = $request->user()->tenant;
        
        if (!$tenant) {
            return response()->json(['error' => 'Tenant não encontrado'], 403);
        }

        // Define o tenant atual globalmente
        app()->singleton('tenant', function () use ($tenant) {
            return $tenant;
        });

        // Define o prefixo do banco de dados se necessário
        config(['database.connections.tenant.database' => 'tenant_' . $tenant->id]);

        return $next($request);
    }
} 