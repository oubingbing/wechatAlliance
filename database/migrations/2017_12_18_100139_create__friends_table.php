<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFriendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friends', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('user_id')->index()->comment('用户Id');
            $table->bigInteger('friend_id')->index()->comment('好友Id');

            $table->string('nickname')->nullable()->comment('好友昵称备注');

            $table->tinyInteger('type')->default(1)->comment('好友类型');

            $table->tinyInteger('status')->default(1)->comment('状态');

            $table->bigInteger('friend_group_id')->nullable()->comment('好友分组Id');

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
        Schema::dropIfExists('friends');
    }
}
