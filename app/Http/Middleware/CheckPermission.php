<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!$request->user()->hasPermission($permission)) {
            return response()->json(['error' => 'Sem permissão'], 403);
        }

        return $next($request);
    }
} 