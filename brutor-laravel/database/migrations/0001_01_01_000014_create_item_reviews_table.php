<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_reviews', function (Blueprint $table) {
            $table->increments('review_id');
            $table->unsignedInteger('item_id');
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('rating');
            $table->text('comment');
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['item_id', 'user_id'], 'item_user_unique');

            $table->foreign('item_id')
                  ->references('item_id')
                  ->on('item')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_reviews');
    }
};
