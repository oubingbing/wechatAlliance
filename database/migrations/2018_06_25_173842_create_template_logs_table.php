<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('app_id')->comment('所属小程序');
            $table->string('open_id')->comment('发送人');
            $table->string('template_id')->comment('模板ID');
            $table->jsonb('content')->comment('发送的内容');
            $table->string('page')->default('')->comment('跳转的页面');

            $table->tinyInteger('status')->default(1)->comment('发送状态，1=成功，2=失败');
            $table->tinyInteger('type')->default(1)->comment('预留字段');

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
        Schema::dropIfExists('template_logs');
    }
}
