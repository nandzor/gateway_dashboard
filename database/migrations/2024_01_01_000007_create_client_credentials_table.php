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
        Schema::create('client_credentials', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id')->nullable();
            $table->smallInteger('type')->nullable();
            $table->smallInteger('is_active')->nullable();
            $table->text('white_list')->nullable();
            $table->text('module_40')->nullable();
            $table->text('avkey')->nullable();
            $table->integer('created_by_id')->nullable();
            $table->smallInteger('prepaid_allow')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_credentials');
    }
};

