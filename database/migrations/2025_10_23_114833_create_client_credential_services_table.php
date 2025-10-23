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
        Schema::create('client_credential_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_credential_id')->constrained('client_credentials')->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->integer('is_active')->default(1);
            $table->timestamps();

            // Add unique constraint to prevent duplicate assignments
            $table->unique(['client_credential_id', 'service_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_credential_services');
    }
};
