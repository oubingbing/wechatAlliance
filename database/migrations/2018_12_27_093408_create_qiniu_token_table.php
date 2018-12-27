<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQiniuTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qiniu_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string("token",1024)->default("")->comment("七牛上传的凭证");
            $table->timestamp('expired_at')->index()->comment("过期时间时间");
            $table->timestamp('created_at')->nullable()->index()->comment("该记录创建的时间");
            $table->timestamp('updated_at')->nullable();
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
        Schema::dropIfExists('qiniu_tokens');
    }
}
