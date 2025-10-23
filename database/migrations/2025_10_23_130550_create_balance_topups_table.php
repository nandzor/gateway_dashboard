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
        Schema::create('balance_topups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Admin who processed
            $table->decimal('amount', 15, 3); // Topup amount
            $table->decimal('previous_balance', 15, 3); // Balance before topup
            $table->decimal('new_balance', 15, 3); // Balance after topup
            $table->string('payment_method')->nullable(); // cash, transfer, credit_card, etc
            $table->string('reference_number')->nullable(); // Payment reference
            $table->text('notes')->nullable(); // Additional notes
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->timestamp('processed_at')->nullable(); // When it was processed
            $table->timestamps();

            // Indexes for better performance
            $table->index(['client_id', 'created_at']);
            $table->index(['status', 'created_at']);
            $table->index('reference_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_topups');
    }
};
