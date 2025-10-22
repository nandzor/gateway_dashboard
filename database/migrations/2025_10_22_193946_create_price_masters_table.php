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
        Schema::create('price_masters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('module_id');
            $table->decimal('price_default', 11, 3);
            $table->boolean('is_active')->default(true);
            $table->string('note', 50)->nullable();
            $table->unsignedBigInteger('currency_id');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('module_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_masters');
    }
};
