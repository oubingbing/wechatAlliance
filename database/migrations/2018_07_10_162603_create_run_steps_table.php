<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRunStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('run_steps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->index()->comment('用户id');

            $table->tinyInteger('type')->default(1)->comment('是否是当天的步数');
            $table->tinyInteger('status')->default(1)->coment('是否已使用，1=未使用，2=已使用');
            $table->bigInteger('step')->default(0)->comment('用户的步数');
            $table->timestamp('run_at')->nullable()->index()->comment('步数的日期');

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
        Schema::dropIfExists('run_steps');
    }
}
