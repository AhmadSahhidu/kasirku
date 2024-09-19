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
        Schema::create('flow_debts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('number')->unique();
            $table->uuid('supplier_id')->nullable();
            $table->string('no_invoice')->nullable();
            $table->integer('amount')->default(0);
            $table->integer('payment_method')->default(1);
            $table->date('due_date')->nullable();
            $table->date('tanggal')->nullable();
            $table->integer('paid_debt')->default(0);
            $table->integer('remaining_debt')->default(0);
            $table->string('status')->default('Belum Lunas');
            $table->uuid('store_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flow_debts');
    }
};
