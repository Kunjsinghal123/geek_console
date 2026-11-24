<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleAccount extends Model
{
    protected $fillable = [
        'store_id',
        'google_id',
        'google_name',
        'google_email',
        'google_avatar',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'is_connected',
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'is_connected' => 'boolean',
    ];

    // public function store()
    // {
    //     return $this->belongsTo(Store::class);
    // }
}
