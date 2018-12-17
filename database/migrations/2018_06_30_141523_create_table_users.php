<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ava')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('zip_code', 6)->nullable();
            $table->date('birthday')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('relationship', 20)->nullable();
            $table->string('nationality')->nullable();
            $table->string('website')->nullable();
            $table->string('lowest_salary')->nullable();
            $table->string('highest_salary')->nullable();
            $table->text('summary')->nullable();
            $table->string('video_summary')->nullable();
            $table->integer('total_exp')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('users');
    }
}
