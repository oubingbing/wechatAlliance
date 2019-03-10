<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('app_id')->index()->comment('小程序id');

            $table->string('nickname')->comment('用户微信昵称');
            $table->string('password')->default("")->comment('预留账号密码');
            $table->string('mobile')->default("")->index()->comment('预留手机号码字段');

            $table->string('avatar')->default("")->comment('微信头像');
            $table->tinyInteger('gender')->default(0)->comment('默认一个性别');
            $table->string('open_id')->unique()->default("")->index();
            $table->string('union_id')->unique()->default("")->index();
            $table->string('city')->default("");
            $table->string('country')->default("");
            $table->string('language')->default("zh_CN");
            $table->string('province')->default("");

            $table->tinyInteger('type')->default(1)->comment('用户类型');
            $table->tinyInteger('status')->default(0)->comment('用户状态');

            $table->rememberToken();

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
        Schema::dropIfExists('users');
    }
}
