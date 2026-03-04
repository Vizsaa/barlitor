<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orderinfo', function (Blueprint $table) {
            $table->increments('orderinfo_id');
            $table->integer('customer_id')->default(0);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->date('date_placed');
            $table->date('date_shipped')->nullable();
            $table->decimal('shipping', 7, 2)->nullable();
            $table->enum('status', ['Processing', 'Delivered', 'Canceled'])->default('Processing');
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orderinfo');
    }
};
