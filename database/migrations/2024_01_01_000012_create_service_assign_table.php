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
        Schema::create('service_assign', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->integer('service_id');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Unique constraint
            $table->unique(['client_id', 'service_id'], 'service_assign_client_id_service_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_assign');
    }
};

