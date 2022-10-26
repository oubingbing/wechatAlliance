<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFollowUserFieldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('follows', function (Blueprint $table) {
            $table->string("follow_nickname",128)->default("")->comment("关注人昵称");
            $table->string("follow_avatar",512)->default("")->comment("关注人头像");
            $table->string("be_follow_nickname",128)->default("")->comment("被关注人昵称");
            $table->string("be_follow_avatar",512)->default("")->comment("被关注人头像");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('follows', function (Blueprint $table) {
            
        });
    }
}
