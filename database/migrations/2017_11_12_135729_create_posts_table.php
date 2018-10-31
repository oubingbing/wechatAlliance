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
            $table->bigIncrements('id');

            $table->integer('poster_id')->index()->comment('贴子的发表人');

            $table->integer('college_id')->index()->nullable()->comment('所属学校');

            $table->longText('content')->nullable()->comment('贴子的内容');

            $table->jsonb('attachments')->nullable()->comment('贴子的附件,例如图片');

            $table->string('topic',1024)->default('无')->comment('主题,预留字段');

            $table->tinyInteger('type')->default(0)->comment('');

            $table->tinyInteger('status')->default(0)->comment('预留字段');

            $table->boolean('private')->comment('公开还是匿名');

            $table->integer('comment_number')->default(0)->comment('评论数量');
            $table->integer('praise_number')->default(0)->comment('点赞数量');

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
        Schema::dropIfExists('posts');
    }
}
