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
            $table->string('trx_id', 50)->nullable();
            $table->integer('trx_type')->default(1);
            $table->timestamp('trx_date')->nullable();
            $table->integer('module_id')->nullable();
            $table->decimal('price', 15, 3)->nullable();
            $table->double('duration')->nullable();
            $table->integer('is_charge')->nullable();
            $table->string('remote_ip', 20)->nullable();
            $table->smallInteger('is_local')->default(0);
            $table->timestamps();
            $table->string('status', 20)->nullable();
            $table->string('trx_req', 50)->nullable();
            $table->smallInteger('node_id')->nullable();
            $table->smallInteger('is_dashboard')->default(0);
            $table->smallInteger('currency_id')->default(1);

            // Indexes
            $table->index('client_id', 'client');
            $table->index(['client_id', 'is_charge'], 'client, ischarge');
            $table->index('is_charge', 'ischarge');
            $table->index('module_id', 'module');
            $table->index(['client_id', 'module_id'], 'module, client');
            $table->index('trx_id', 'ref_id');
            $table->index('trx_req', 'req_id');
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

