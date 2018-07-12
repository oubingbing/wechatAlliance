<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTarvelPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travel_plans', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('user_id')->index()->comment('用户ID');

            $table->string('title')->default('')->comment('旅行的目标');
            $table->bigInteger('distance')->comment('旅行的路程，单位是米');

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
        Schema::dropIfExists('travel_plans');
    }
}
