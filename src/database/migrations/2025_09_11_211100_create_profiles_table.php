<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->comment('ユーザーID')
                  ->constrained(table: 'users', column: 'id')
                  ->restrictOnDelete();
            $table->string('display_name')->comment('プロフィール名');
            $table->string('image_url')->comment('画像パス');
            $table->foreignId('address_id')->comment('住所ID')
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
        Schema::dropIfExists('profiles');
    }
}
