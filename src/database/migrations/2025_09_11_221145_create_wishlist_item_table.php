<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWishlistItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wishlist_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->comment('ユーザーID')
                ->constrained(table: 'users', column: 'id')
                ->restrictOnDelete();
            $table->foreignId('item_id')->comment('商品ID')
                ->constrained(table: 'items', column: 'id')
                ->restrictOnDelete();
            $table->timestamps();

            // 重複防止（同じ user_id と item_id の組み合わせは1つだけ）
            $table->unique(['user_id', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wishlist_item');
    }
}
