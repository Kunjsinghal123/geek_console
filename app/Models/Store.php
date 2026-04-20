<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Store extends Authenticatable
{
    protected $fillable = [
        'store_id',
        'store_domain',
        'store_name',
        'store_email',
        'store_logo',
        'owner_name',
        'access_token',
        'scope',
        'is_active',
        'installed_at',
        'uninstalled_at',
    ];

    public function getInitialsAttribute()
    {
        if (!$this->store_name) {
            return 'GS';
        }

        $words = array_values(array_filter(explode(' ', $this->store_name)));

        if (count($words) === 1) {
            $name = $words[0];
            return strtoupper(substr($name, 0, 1) . substr($name, -1));
        }

        if (count($words) === 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }

        // 3 or more words → first + last word initials
        return strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
    }
}
