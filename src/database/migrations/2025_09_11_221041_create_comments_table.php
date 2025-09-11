<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->comment('商品ID')
                  ->constrained(table: 'items', column: 'id')
                  ->restrictOnDelete();
            $table->foreignId('user_id')->comment('ユーザーID')
                  ->constrained(table: 'users', column: 'id')
                  ->restrictOnDelete();
            $table->text('body')->comment('コメント');

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
        Schema::dropIfExists('comments');
    }
}
