<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProblemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'problems',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('course_id');
                $table->foreign('course_id')
                    ->references('id')->on('courses');
                $table->unsignedBigInteger('assignment_id');
                $table->foreign('assignment_id')
                    ->references('id')->on('assignments');
                $table->string('content');
                $table->timestamps();
                $table->softDeletes();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('problems');
    }
}