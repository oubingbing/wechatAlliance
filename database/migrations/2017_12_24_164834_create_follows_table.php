<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follows', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('user_id')->index()->comment('关注人');
            $table->bigInteger('obj_id')->index()->comment('关注的对象');

            $table->tinyInteger('obj_type')->default(1)->comment('关注对象的类型,1=表白墙,2=卖舍友,3=评论暗恋匹配,4=评论');

            $table->tinyInteger('status')->default(1)->comment('是否取消关注,1=关注中,2=已取消关注');

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
        Schema::dropIfExists('follows');
    }
}
