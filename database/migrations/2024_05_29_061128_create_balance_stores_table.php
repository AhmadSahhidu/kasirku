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
        Schema::create('balance_stores', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('amount_in_hand')->nullable();
            $table->integer('amount_in_cashier')->nullable();
            $table->integer('amount_customer_debt')->nullable();
            $table->uuid('store_id')->nullable();
            $table->integer('grand_total')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_stores');
    }
};
