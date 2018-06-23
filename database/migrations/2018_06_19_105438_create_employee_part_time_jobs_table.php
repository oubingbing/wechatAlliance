<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeePartTimeJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_part_time_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('part_time_job_id')->index()->comment('悬赏ID');
            $table->bigInteger('user_id')->index()->comment('赏金猎人ID');
            $table->tinyInteger('status')->default(1)->comment('于悬赏的状态，1=执行任务中，2=被雇主不信任解除雇佣关系,3=任务完成');
            $table->tinyInteger('score')->default(1)->comment('任务好评，1=好评，2=中评，3=差评');
            $table->longText('comments')->nullable()->comment('文字评论');
            $table->jsonb('attachments')->comment('评论附件');

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
        Schema::dropIfExists('employee_part_time_jobs');
    }
}
