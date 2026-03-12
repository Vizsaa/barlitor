<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barcode', function (Blueprint $table) {
            $table->char('barcode_ean', 13)->primary();
            $table->unsignedInteger('item_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barcode');
    }
};
