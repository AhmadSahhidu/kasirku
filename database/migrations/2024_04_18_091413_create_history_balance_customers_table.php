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
        Schema::create('history_balance_customers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('balance_id');
            $table->string('number')->unique();
            $table->string('nominal');
            $table->string('status');
            $table->string('note');
            $table->string('start_balance');
            $table->string('end_balance');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_balance_customers');
    }
};
