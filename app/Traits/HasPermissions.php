<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait HasPermissions
{
    public function hasPermission($permission)
    {
        return Cache::remember("user_{$this->id}_permissions", 3600, function () use ($permission) {
            return $this->roles()
                ->whereHas('abilities', function ($query) use ($permission) {
                    $query->where('name', $permission);
                })->exists();
        });
    }
} 