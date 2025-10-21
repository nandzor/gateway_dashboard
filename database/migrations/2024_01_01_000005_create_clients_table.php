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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('client_name', 100)->nullable();
            $table->string('address', 150)->nullable();
            $table->string('contact', 20)->nullable();
            $table->smallInteger('type')->nullable();
            $table->string('ak', 100)->nullable();
            $table->string('sk', 100)->nullable();
            $table->string('avkey_iv', 100)->nullable();
            $table->string('avkey_pass', 100)->nullable();
            $table->integer('service_module')->nullable();
            $table->integer('is_active')->nullable();
            $table->timestamps();
            $table->text('service_allow')->nullable();
            $table->text('white_list')->nullable();
            $table->text('module_40')->nullable();
            $table->smallInteger('is_staging')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};

