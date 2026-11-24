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
        Schema::create('google_accounts', function (Blueprint $table) {
            $table->id();

            // Relation with store
            // $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->foreignId('store_id')->constrained();

            // $table->foreignId('store_id')->unique()->constrained()->cascadeOnDelete();

            // Google User Info
            $table->string('google_id')->nullable();
            $table->string('google_name')->nullable();
            $table->string('google_email')->nullable();
            $table->string('google_avatar')->nullable();

            // Tokens
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();

            // Status
            $table->boolean('is_connected')->default(false);

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('google_accounts');
    }
};
