<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAgencies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agencies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ava')->nullable();
            $table->string('email')->unique();
            $table->string('company');
            $table->string('kantor_pusat', 60);
            $table->integer('industry_id')->unsigned();
            $table->foreign('industry_id')->references('id')->on('industries')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->text('tentang');
            $table->text('alasan');
            $table->string('link', 255);
            $table->string('alamat', 200);
            $table->string('phone');
            $table->string('hari_kerja', 30);
            $table->string('jam_kerja', 30);
            $table->double('lat', 20, 10)->nullable();
            $table->double('long', 20, 10)->nullable();
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
        Schema::dropIfExists('agency');
    }
}
