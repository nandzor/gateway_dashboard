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
            $table->integer('module_id')->nullable();
            $table->decimal('price_default', 15, 3)->nullable();
            $table->smallInteger('is_active')->nullable();
            $table->string('note', 50)->nullable();
            $table->timestamps();
            $table->smallInteger('currency_id')->default(1);
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

