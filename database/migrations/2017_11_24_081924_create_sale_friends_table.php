<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleFriendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_friends', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('owner_id')->index()->comment('所属人Id');
            $table->bigInteger('college_id')->index()->nullable()->comment('学校Id');

            $table->string('name')->index()->comment('舍友的名字');
            $table->tinyInteger('gender')->default(1)->comment('性别,默认是男');
            $table->string('major')->nullable()->comment('专业');
            $table->string('expectation',1024)->comment('简单介绍下喜欢什么样的人,期望');
            $table->longText('introduce')->comment('介绍一下舍友');
            $table->jsonb('attachments')->nullable()->comment('贴子的附件,例如图片');

            $table->integer('comment_number')->default(0)->comment('评论数量');
            $table->integer('praise_number')->default(0)->comment('点赞数量');

            $table->tinyInteger('type')->default(1)->comment('预留字段');
            $table->tinyInteger('status')->default(1)->comment('预留字段');

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
        Schema::dropIfExists('sale_friends');
    }
}
