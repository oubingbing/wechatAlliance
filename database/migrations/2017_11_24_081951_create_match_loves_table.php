<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchLovesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_loves', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('owner_id')->index()->comment('所有者');
            $table->bigInteger('college_id')->index()->nullable()->comment('学校');

            $table->string('user_name')->index()->comment('匹配人的名字');
            $table->string('match_name')->index('被匹配人的名字');
            $table->longText('content')->nullable()->comment('想对他说的话');
            $table->jsonb('attachments')->nullable()->comment('贴子的附件,例如图片');

            $table->tinyInteger('private')->default(1)->comment('是否匿名,默认否');

            $table->tinyInteger('is_password')->defalu(1)->comment('是否需要密码,默认需要');
            $table->string('password')->nullable()->comment('设定的密码');
            $table->tinyInteger('type')->default(1)->comment('类型,是否匿名,默认匿名');
            $table->tinyInteger('status')->defauult(1)->commnet('匹配状态,是否匹配成功,1=未成功,2=已成功,3重复匹配');

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
        Schema::dropIfExists('match_loves');
    }
}
