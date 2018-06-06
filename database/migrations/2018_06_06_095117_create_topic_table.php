<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('user_id')->index()->comment('话题发布者，可以是后台管理员和用户');
            $table->bigInteger('app_id')->comment('所属应用id');
            $table->tinyInteger('user_type')->default(1)->comment('发帖人类型，1=后台管理员，2=用户');

            $table->string('title')->nullable()->comment('标题');
            $table->longText('content')->nullable()->comment('内容');
            $table->jsonb('attachments')->nullable()->comment('附件');

            $table->bigInteger('praise_number')->default(0)->comment('点赞人数');
            $table->bigInteger('view_number')->default(0)->comment('浏览人数');
            $table->bigInteger('comment_number')->default()->comment('评论人数');

            $table->tinyInteger('status')->default(1)->comment('状态，1=下架，2=上架');

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
        Schema::dropIfExists('topics');
    }
}
