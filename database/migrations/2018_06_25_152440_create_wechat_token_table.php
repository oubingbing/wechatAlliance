<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWechatTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('app_id')->index()->comment('app_id');
            $table->string('token')->comment('access_token');
            $table->timestamp('expired_at')->index()->comment('过期时间');

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
        Schema::dropIfExists('wechat_tokens');
    }
}
