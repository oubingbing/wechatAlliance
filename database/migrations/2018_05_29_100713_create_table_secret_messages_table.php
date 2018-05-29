<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSecretMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('secret_messages', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('post_user_id')->index()->comment('发送人用户ID');
            $table->bigInteger('receive_user_id')->index()->comment('接收人id');
            $table->bigInteger('message_session_id')->index()->comment('短信会话ID');

            $table->string('number')->default('0000')->index()->comment('编号');
            $table->string('code')->defalut('0000')->index()->commemt('读信验证码');

            $table->longText('content')->nullable()->comment('内容');
            $table->jsonb('attachments')->nullable()->comment('附件的内容');

            $table->tinyInteger('status')->default(1)->comment('是否已读，1=未读，2=已读');

            $table->timestamp('delay_at')->index()->comment('延期发送的时间');
            $table->timestamp('send_at')->nullable()->comment('短信发送的日期');

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
        Schema::dropIfExists('secret_messages');
    }
}
