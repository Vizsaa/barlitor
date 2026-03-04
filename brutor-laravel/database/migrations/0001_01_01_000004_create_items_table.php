<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item', function (Blueprint $table) {
            $table->increments('item_id');
            $table->text('title');
            $table->string('description', 64);
            $table->decimal('cost_price', 7, 2)->nullable();
            $table->decimal('sell_price', 7, 2)->nullable();
            $table->text('image_path');
            $table->timestamps();
            $table->softDeletes();
            $table->string('category', 50)->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->unsignedInteger('supplier_id')->nullable();
            $table->enum('type', ['product', 'tool'])->default('product');

            $table->foreign('supplier_id')
                  ->references('supplier_id')
                  ->on('suppliers')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item');
    }
};
