<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMessageSessionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_sessions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('user_id')->index()->comment('用户ID');
            $table->string('post_phone')->nullable()->index()->comment('发送人的手机号码');
            $table->string('receive_phone')->index()->comment('接收人人的手机号码');

            $table->tinyInteger('obj_type')->comment('消息对象类型，1=表白墙，2=卖舍友，3=暗恋匹配，4=密语');
            $table->bigInteger('obj_id')->index()->comment('对象ID');

            $table->timestamp('created_at')->nullable()->index();
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_sessions');
    }
}
