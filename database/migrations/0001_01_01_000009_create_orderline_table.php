<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orderline', function (Blueprint $table) {
            $table->unsignedInteger('orderinfo_id');
            $table->unsignedInteger('product_id');
            $table->integer('quantity');
            $table->decimal('rate', 7, 2)->default(0.00);

            $table->primary(['orderinfo_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orderline');
    }
};
