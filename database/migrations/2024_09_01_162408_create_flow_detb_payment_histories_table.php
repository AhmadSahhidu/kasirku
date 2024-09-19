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
        Schema::create('flow_detb_payment_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('flow_debt_id')->nullable();
            $table->date('tanggal')->nullable();
            $table->integer('payment_method')->nullable();
            $table->integer('amount')->default(0);
            $table->integer('paid_debt')->default(0);
            $table->integer('remaining_debt')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flow_detb_payment_histories');
    }
};
