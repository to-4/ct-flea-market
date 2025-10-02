<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->comment('商品ID')
                  ->constrained(table: 'items', column: 'id')
                  ->restrictOnDelete();
            $table->foreignId('category_id')->comment('カテゴリID')
                  ->constrained(table: 'categories', column: 'id')
                  ->restrictOnDelete();
            $table->timestamps();

            // 重複防止（同じ item_id と category_id の組み合わせは1つだけ）
            $table->unique(['item_id', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_item');
    }
}
