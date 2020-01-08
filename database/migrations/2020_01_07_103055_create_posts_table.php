<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('send_id')->unsigned();
            $table->integer('receive_id')->unsigned()->index();
            $table->string('content');
            // 0が未判定、1がgood、2がbad
            $table->integer('judge')->unsigned()->default(0);
            $table->timestamps();
            
            //外部キー
            $table->foreign('receive_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
