<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartTimeJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('part_time_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('user_id')->index()->comment('悬赏人ID');
            $table->string('title')->default('')->comment('悬赏标题');
            $table->longText('content')->nullable()->comment('悬赏内容');
            $table->jsonb('attachments')->comment('悬赏附件');
            $table->float('salary')->default(0)->comment('悬赏酬劳');

            $table->tinyInteger('status')->default(1)->comment('悬赏的状态，1=悬赏中，2=任务中，3=悬赏终止，4=悬赏过期，5=悬赏完成');
            $table->tinyInteger('type')->default(1)->comment('预留字段');

            $table->timestamp('end_at')->nullable()->comment('悬赏令的有效期')->index();

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
        Schema::dropIfExists('part_time_jobs');
    }
}
