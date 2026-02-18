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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('customer_number');
            $table->string('product_name');
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['pending', 'processing', 'success', 'failed'])->default('pending');
            $table->string('payment_proof')->nullable();
            $table->string('sn_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
