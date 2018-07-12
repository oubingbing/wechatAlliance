<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTarvelLogPoiTable extends Migration
{
    /**
     * 途径附近的生活美食餐饮服务
     *
     * @author yezi
     *
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travel_log_pois', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('travel_log_id')->index()->comment('所属旅行日志');

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
        Schema::dropIfExists('travel_log_pois');
    }
}
