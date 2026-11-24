<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('google_search_properties', function (Blueprint $table) {
            $table->id();

            $table->foreignId('store_id') ->constrained('stores') ->cascadeOnDelete(); 
            $table->string('site_url'); 
            $table->string('property_type') ->default('SITE'); 
            $table->string('verification_method') ->nullable(); 
            $table->text('verification_token') ->nullable(); 
            $table->boolean('is_verified') ->default(false); 
            $table->boolean('is_added_to_search_console') ->default(false); 
            $table->timestamp('verified_at') ->nullable(); 
            $table->timestamps(); 
            $table->unique([ 'store_id', 'site_url' ]); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('google_search_properties');
    }
};
