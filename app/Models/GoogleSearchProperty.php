<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleSearchProperty extends Model
{
    protected $fillable = [ 
        'store_id', 
        'site_url', 
        'property_type', 
        'verification_method', 
        'verification_token', 
        'is_verified', 
        'is_added_to_search_console', 
        'verified_at', 
    ]; 
        
    protected $casts = [ 
        'is_verified' => 'boolean', 
        'is_added_to_search_console' => 'boolean', 
        'verified_at' => 'datetime', 
    ];

    public function store() { return $this->belongsTo(Store::class); }
}
