<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableVacancy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacancy', function (Blueprint $table) {
            $table->increments('id');
            $table->string('judul', 200);
            $table->integer('agency_id')->unsigned();
            $table->foreign('agency_id')->references('id')->on('agencies')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->integer('city_id')->unsigned();
            $table->foreign('city_id')->references('id')->on('cities')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->text('syarat');
            $table->text('tanggungjawab');
            $table->integer('pengalaman')->unsigned();
            $table->integer('jobtype_id')->unsigned();
            $table->foreign('jobtype_id')->references('id')->on('job_types')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->integer('industry_id')->unsigned();
            $table->foreign('industry_id')->references('id')->on('industries')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->integer('joblevel_id')->unsigned();
            $table->foreign('joblevel_id')->references('id')->on('job_levels')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->integer('salary_id')->unsigned();
            $table->foreign('salary_id')->references('id')->on('salaries')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->integer('degree_id')->unsigned();
            $table->foreign('degree_id')->references('id')->on('degrees')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->integer('major_id')->unsigned();
            $table->foreign('major_id')->references('id')->on('majors')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->integer('jobfunction_id')->unsigned();
            $table->foreign('jobfunction_id')->references('id')->on('job_functions')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->boolean('isPost')->default(false);
            $table->date('recruitmentDate_start')->nullable();
            $table->date('recruitmentDate_end')->nullable();
            $table->date('interview_date')->nullable();
            $table->boolean('isSISKA')->default(false);
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
        Schema::dropIfExists('vacancy');
    }
}
