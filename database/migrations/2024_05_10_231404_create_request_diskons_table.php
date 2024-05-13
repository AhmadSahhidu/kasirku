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
        Schema::create('request_diskons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('number');
            $table->uuid('sale_id')->nullable();
            $table->integer('amount_discount')->default(0);
            $table->string('note')->nullable();
            $table->integer('status');
            $table->uuid('user_id')->nullable();
            $table->uuid('confirm_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_diskons');
    }
};
