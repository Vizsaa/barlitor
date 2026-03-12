<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rental', function (Blueprint $table) {
            $table->increments('rental_id');
            $table->unsignedInteger('transaction_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedInteger('item_id');
            $table->date('start_date');
            $table->date('due_date');
            $table->decimal('rate_charged', 7, 2);
            $table->integer('quantity')->default(1);

            $table->foreign('transaction_id')
                  ->references('orderinfo_id')
                  ->on('orderinfo')
                  ->onDelete('cascade');

            $table->foreign('customer_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('item_id')
                  ->references('item_id')
                  ->on('item')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental');
    }
};
