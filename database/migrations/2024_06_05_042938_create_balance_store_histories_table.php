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
        Schema::create('balance_store_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('balance_store_id')->nullable();
            $table->integer('amount')->default(0);
            $table->integer('balance_start')->default(0);
            $table->integer('balance_end')->default(0);
            $table->integer('type')->default(1)->nullable();
            $table->integer('sumber')->nullable();
            $table->string('description')->nullable();
            $table->date('tgl')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_store_histories');
    }
};
