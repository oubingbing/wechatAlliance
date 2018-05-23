<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserVisitLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_visit_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('nickname')->nullable()->comment('用户微信昵称');
            $table->bigInteger('user_id')->index()->comment('用户Id');

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
        Schema::table('user_visit_logs', function (Blueprint $table) {
            //
        });
    }
}
