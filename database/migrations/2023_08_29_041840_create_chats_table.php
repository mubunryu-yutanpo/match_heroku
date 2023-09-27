<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user1_id')->unsigned();
            $table->bigInteger('user2_id')->unsigned();
            $table->timestamps();

            // 外部キー
            $table->foreign('user1_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user2_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            // 同じ組み合わせに対して1つのチャットを作成
            $table->unique(['user1_id', 'user2_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chats');
    }
}
