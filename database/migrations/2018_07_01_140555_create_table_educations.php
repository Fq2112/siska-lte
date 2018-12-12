<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEducations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('educations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->integer('degree_id')->unsigned();
            $table->foreign('degree_id')->references('id')->on('degrees')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->integer('major_id')->unsigned();
            $table->foreign('major_id')->references('id')->on('majors')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->text('awards')->nullable();
            $table->string('school_name', 100)->nullable();
            $table->string('start_period', 4);
            $table->string('end_period', 4)->nullable();
            $table->string('nilai', 4)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('educations');
    }
}
