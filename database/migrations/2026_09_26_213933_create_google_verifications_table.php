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
        Schema::create('google_verifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('store_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('site_url');

            $table->text('verification_token');

            $table->string('verification_method')
                ->default('META');

            $table->boolean('verified')
                ->default(false);

            $table->timestamps();

            $table->unique(['store_id', 'site_url']);
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('google_verifications');
    }
};
