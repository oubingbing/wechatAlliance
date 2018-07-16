<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTarvelLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travel_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('travel_plan_id')->index()->comment('旅行计划id');
            $table->bigInteger('user_id')->index()->comment('用户id');

            $table->string('name')->default('')->comment('抵达点的名字');
            $table->string('address')->default('')->comment('抵达点的地址');
            $table->string('province')->default('')->comment('省');
            $table->string('city')->default('')->comment('城市');
            $table->string('district')->default('')->comment('县');

            $table->bigInteger('point_id')->nullable()->comment('所属站点');
            $table->float('length')->default(0)->comment('地图坐标的距离');
            $table->float('total_length')->default(0)->comment('总的地图坐标的距离');

            $table->float('distance')->default(0)->comment('行程');
            $table->float('step')->default(0)->comment('步数');
            $table->string('latitude')->default('')->comment('抵达点地理维度');
            $table->string('longitude')->default('')->comment('抵达点地理经度');
            $table->timestamp('run_at')->nullable()->index()->comment('日期');

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
        Schema::dropIfExists('travel_logs');
    }
}
