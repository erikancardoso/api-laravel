<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory, HasUlids;

    // Define que esse model usará a conexão central
    protected $connection = 'central';

    protected $fillable = [
        'name',
        'domain',
        'database',
    ];

    /**
     * (Opcional) Se precisar de lógica customizada, você pode sobrescrever o create.
     */
    public static function create(array $attributes)
    {
        $tenant = new static;
        $tenant->fill($attributes);
        $tenant->save();
        return $tenant;
    }
}
