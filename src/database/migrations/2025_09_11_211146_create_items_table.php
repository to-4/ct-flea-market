<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('商品名');
            $table->integer('price')->comment('料金');
            $table->string('brand_name')->nullable()->comment('メーカー名');
            $table->text('description')->comment('説明');
            $table->string('image_url')->comment('画像パス');
            $table->foreignId('user_id')->comment('ユーザーID')
                  ->constrained(table: 'users', column: 'id')
                  ->restrictOnDelete();
            $table->foreignId('item_condition_id')->comment('状態ID')
                  ->constrained(table: 'item_conditions', column: 'id')
                  ->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
