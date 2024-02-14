<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporanTable extends Migration
{
    public function up()
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('deskripsi');
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->double('jarak')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->tinyInteger('jenis')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
