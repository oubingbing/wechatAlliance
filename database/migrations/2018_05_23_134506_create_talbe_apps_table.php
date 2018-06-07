<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTalbeAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apps', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name')->comment('小程序的名字');
            $table->string('app_key')->index()->comment('小程序的APP key');
            $table->string('app_secret')->comment('小程序的密钥');
            $table->string('alliance_key')->index()->comment('联盟给的身份标识，接口需要传递这个key');
            $table->string('domain')->comment('小程序的接口域名');
            $table->string('mobile')->comment('联系人手机号码');
            $table->jsonb('attachments')->nullable()->comment('小程序相关图片');

            $table->bigInteger('college_id')->index()->nullable()->comment('学校');
            $table->bigInteger('service_id')->index()->nullable()->comment('客服id,users表的id');

            $table->tinyInteger('status')->default(1)->comment('小程序的状态，1=待审核，2=正常上线，3=微信审核中，4=下线');

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
        Schema::dropIfExists('apps');
    }
}
