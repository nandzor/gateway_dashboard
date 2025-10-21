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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->char('username', 150)->nullable();
            $table->char('email', 50)->nullable();
            $table->char('name', 100)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->char('password', 100)->nullable();
            $table->char('remember_token', 150)->nullable();
            $table->smallInteger('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

