<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('commenter_id')->index()->comment('评论人');
            $table->bigInteger('obj_id')->index()->comment('改评论所属的贴子');
            $table->bigInteger('college_id')->nullable()->comment('学校');

            $table->longText('content')->nullable()->comment('评论的内容');
            $table->jsonb('attachments')->nullable()->comment('评论的附件,例图片');

            $table->bigInteger('ref_comment_id')->nullable()->comment('改评论所评论的评论Id');
            $table->tinyInteger('obj_type')->default(1)->comment('评论的对象的类型,默认是1=表白墙');

            $table->tinyInteger('type')->default(0)->comment('评论的类型');
            $table->tinyInteger('status')->default(0)->comment('评论的状态');

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
        Schema::dropIfExists('comments');
    }
}
