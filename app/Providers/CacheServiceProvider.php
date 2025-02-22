<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;

class CacheServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Cache::extend('tenant', function ($app) {
            $tenant = app('tenant');
            $prefix = "tenant_{$tenant->id}:";
            
            return Cache::repository(
                Cache::store()->getStore()->setPrefix($prefix)
            );
        });
    }
} 