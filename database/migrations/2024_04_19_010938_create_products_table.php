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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('number');
            $table->string('name');
            $table->uuid('supplier_id')->nullable();
            $table->uuid('brand_id')->nullable();
            $table->uuid('category_id')->nullable();
            $table->uuid('store_id')->nullable();
            $table->string('seri');
            $table->string('size');
            $table->string('satuan');
            $table->integer('stock');
            $table->integer('stock_minimum')->nullable();
            $table->integer('purchase_price');
            $table->integer('selling_price');
            $table->uuid('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
