<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->comment('ユーザID')
                  ->constrained(table: 'users', column: 'id')
                  ->restrictOnDelete();
            $table->string('postal_code', 10)->comment('郵便番号');
            $table->string('address_line1')->comment('住所１');
            $table->string('address_line2')->nullable()->comment('住所２');
            $table->boolean('is_default')->comment('デフォルトフラグ');
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
        Schema::dropIfExists('addresses');
    }
}
