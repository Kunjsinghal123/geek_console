<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleVerification extends Model
{
    protected $fillable = [
        'shop_id',
        'site_url',
        'verification_token',
        'verification_method',
        'verified',
    ];

    protected $casts = [
        'verification_token' => 'encrypted',
        'verified' => 'boolean',
    ];
}
