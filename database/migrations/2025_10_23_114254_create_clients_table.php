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
            $table->string('client_name');
            $table->text('address')->nullable();
            $table->string('contact')->nullable();
            $table->integer('type')->default(1); // 1 = Prepaid, 2 = Postpaid
            $table->string('ak')->nullable(); // Access Key
            $table->string('sk')->nullable(); // Secret Key
            $table->string('avkey_iv')->nullable(); // AES IV
            $table->string('avkey_pass')->nullable(); // AES Password
            $table->integer('service_module')->nullable(); // Foreign key to services
            $table->integer('is_active')->default(1);
            $table->text('service_allow')->nullable(); // JSON array of service IDs
            $table->text('white_list')->nullable(); // JSON array of IP addresses
            $table->integer('module_40')->default(0);
            $table->integer('is_staging')->default(0);
            $table->timestamps();
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
