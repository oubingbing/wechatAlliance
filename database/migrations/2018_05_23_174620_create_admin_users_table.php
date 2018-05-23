<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('username')->comment('用户微信昵称');
            $table->string('email')->unique()->index()->comment('邮箱');
            $table->string('password')->nullable()->comment('预留账号密码');
            $table->string('mobile')->nullable()->index()->comment('预留手机号码字段');

            $table->tinyInteger('status')->default(0)->comment('用户状态');

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
        Schema::dropIfExists('admin_users');
    }
}
