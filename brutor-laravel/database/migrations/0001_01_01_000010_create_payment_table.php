<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment', function (Blueprint $table) {
            $table->increments('payment_id');
            $table->unsignedInteger('transaction_id');
            $table->decimal('amount_paid', 10, 2);
            $table->timestamp('paid_on')->useCurrent();

            $table->foreign('transaction_id')
                  ->references('orderinfo_id')
                  ->on('orderinfo')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
