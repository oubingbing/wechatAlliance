<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInboxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inboxes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('from_id')->index()->comment('发送者');
            $table->bigInteger('to_id')->index()->comment('接收者');

            $table->string('content',1024)->comment('信箱的内容');

            $table->bigInteger('obj_id')->index()->commment('对象的Id');
            $table->tinyInteger('obj_type')->comment('对象的类型');
            $table->tinyInteger('action_type')->comment('信箱的操作类型,例如发帖,评论,回复评论,点赞,关注');

            $table->timestamp('post_at')->nullable()->comment('发送的时间');
            $table->timestamp('read_at')->nullable()->comment('阅读的时间');

            $table->boolean('private')->comment('公开还是匿名新建');

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
        Schema::dropIfExists('inboxes');
    }
}
