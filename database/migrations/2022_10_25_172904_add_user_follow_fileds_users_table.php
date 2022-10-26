<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserFollowFiledsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string("personal_signature",512)->default("你在路上随便碰到的一个路人，都是别人做梦都想见到的那人")->comment("个性签名");
            $table->integer("follow_num")->default(0)->comment("关注数");
            $table->integer("fans_num")->default(0)->comment("粉丝数");
            $table->integer("post_num")->default(0)->comment("帖子动态数");
            $table->integer("clock_num")->default(0)->comment("打卡天数");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
