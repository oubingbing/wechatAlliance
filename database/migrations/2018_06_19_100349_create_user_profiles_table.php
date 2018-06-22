<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('user_id')->index()->comment('用户ID');
            $table->string('nickname')->default('')->comment('微信初始昵称');
            $table->string('avatar')->default('')->comment('微信初始头像');
            $table->string('name',128)->default('')->comment('用户证实姓名');
            $table->string('student_number')->default('')->comment('学号');
            $table->tinyInteger('grade')->dafault(1)->comment('用户年级,1=大一，2=大二，3=大三，4=大四，5=其他');
            $table->string('major')->default('')->comment('专业');
            $table->string('college')->default('')->comment('所属学院');

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
        Schema::dropIfExists('user_profiles');
    }
}
