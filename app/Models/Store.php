<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'store_id',
        'store_domain',
        'store_name',
        'store_email',
        'owner_name',
        'access_token',
        'scope',
        'is_active',
        'installed_at',
        'uninstalled_at',
    ];
}
