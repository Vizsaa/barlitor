<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products_sold', function (Blueprint $table) {
            $table->increments('materials_sold_id');
            $table->unsignedInteger('transaction_id');
            $table->unsignedInteger('product_id');
            $table->integer('quantity');
            $table->decimal('rate_charged', 7, 2);

            $table->foreign('transaction_id')
                  ->references('orderinfo_id')
                  ->on('orderinfo')
                  ->onDelete('cascade');

            $table->foreign('product_id')
                  ->references('item_id')
                  ->on('item')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products_sold');
    }
};
