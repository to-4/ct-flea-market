<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->comment('ユーザID')
                  ->constrained(table: 'users', column: 'id')
                  ->restrictOnDelete();
            $table->foreignId('item_id')->comment('商品ID')
                  ->constrained(table: 'items', column: 'id')
                  ->restrictOnDelete();
            $table->foreignId('payment_method_id')->comment('支払い方法ID')
                  ->constrained(table: 'payment_methods', column: 'id')
                  ->restrictOnDelete();
            $table->foreignId('shipping_address_id')->comment('送付先住所ID')
                  ->constrained(table: 'addresses', column: 'id')
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
        Schema::dropIfExists('purchases');
    }
}
