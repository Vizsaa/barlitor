<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->increments('customer_id');
            $table->char('title', 4)->nullable();
            $table->string('fname', 32)->nullable();
            $table->string('lname', 32);
            $table->string('addressline', 64)->nullable();
            $table->string('town', 32)->nullable();
            $table->char('zipcode', 10);
            $table->string('phone', 16)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};
