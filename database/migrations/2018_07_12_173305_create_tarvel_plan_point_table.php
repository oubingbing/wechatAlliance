<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTarvelPlanPointTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travel_plan_points', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('travel_plan_id')->index()->comment('旅行计划');
            $table->string('name')->default('')->comment('站点的名字');
            $table->string('address')->default()->comment('站点的地址');

            $table->string('latitude')->default('')->comment('站点地理维度');
            $table->string('longitude')->default('')->comment('站点地理经度');

            $table->integer('sort')->default(0)->comment('站点的顺序');
            $table->tinyInteger('type')->default(1)->comment('站点的类型，1=起点，2=途径站点，3=终点');

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
        Schema::dropIfExists('travel_plans_point');
    }
}
