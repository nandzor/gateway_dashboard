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
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->smallInteger('client_type')->nullable();
            $table->string('trx_id', 50)->nullable()->default('reff_id');
            $table->integer('trx_type')->nullable()->default(1);
            $table->timestamp('trx_date')->nullable();
            $table->integer('module_id')->nullable();
            $table->decimal('price', 11, 3)->nullable();
            $table->double('duration')->nullable();
            $table->integer('is_charge')->nullable();
            $table->string('remote_ip', 20)->nullable()->default('default');
            $table->smallInteger('is_local')->nullable()->default(0);
            $table->string('status', 20)->nullable()->default('default');
            $table->string('trx_req', 50)->nullable()->default('req_id');
            $table->smallInteger('node_id')->nullable();
            $table->smallInteger('is_dashboard')->nullable()->default(0);
            $table->smallInteger('currency_id')->nullable()->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
