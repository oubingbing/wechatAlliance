<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplateKeyWorldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_key_words', function (Blueprint $table) {
            $table->increments('id');

            $table->string('keyword',128)->comment('模板消息ID');
            $table->string('title')->comment('标题');
            $table->string('content')->nullable()->comment('内容');
            $table->jsonb('keyword_ids')->comment('消息模板关键字组合模板排列ID');

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
        Schema::dropIfExists('template_key_worlds');
    }
}
