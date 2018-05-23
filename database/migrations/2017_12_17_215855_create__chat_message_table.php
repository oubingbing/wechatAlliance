<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('from_user_id')->index()->comment('发送消息者');
            $table->bigInteger('to_user_id')->index()->comment('接受信息者');

            $table->longText('content')->nullable()->comment('内容');
            $table->jsonb('attachments')->nullable()->comment('附件');

            $table->tinyInteger('type')->default(1)->comment('消息类型');
            $table->tinyInteger('status')->default(1)->comment('接受状态');

            $table->timestamp('post_at')->nullable()->comment('发送的时间');
            $table->timestamp('read_at')->nullable()->comment('阅读的时间');

            $table->timestamp('created_at')->nullable()->index();
            $table->timestamp('updated_at')->nullable()->index();

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
        Schema::dropIfExists('chat_messages');
    }
}
