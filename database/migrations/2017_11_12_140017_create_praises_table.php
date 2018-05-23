<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePraisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('praises', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('owner_id')->index()->comment('点赞人');

            $table->bigInteger('obj_id')->index()->comment('被点赞对象Id');
            $table->tinyInteger('obj_type')->default(0)->comment('被点赞对象的类型');
            $table->bigInteger('college_id')->nullable()->comment('学校Id');

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
        Schema::dropIfExists('praises');
    }
}
