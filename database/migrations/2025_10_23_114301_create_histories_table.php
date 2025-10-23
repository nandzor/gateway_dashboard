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
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->integer('client_type')->default(1); // 1 = Prepaid, 2 = Postpaid
            $table->string('trx_id')->nullable();
            $table->integer('trx_type')->default(1); //
            $table->timestamp('trx_date')->nullable();
            $table->foreignId('module_id')->constrained('services')->onDelete('cascade');
            $table->decimal('price', 15, 3)->default(0);
            $table->float('duration')->nullable();
            $table->integer('is_charge')->default(0);
            $table->string('remote_ip')->nullable();
            $table->integer('is_local')->default(0);
            $table->string('status')->default('OK'); // OK, ERROR, PENDING, INVALID REQUEST
            $table->text('trx_req')->nullable(); // JSON request data
            $table->integer('node_id')->nullable();
            $table->integer('is_dashboard')->default(0);
            $table->foreignId('currency_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();

            // Add indexes for better performance
            $table->index(['client_id', 'trx_date']);
            $table->index(['status', 'trx_date']);
            $table->index(['trx_type', 'trx_date']);
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
