<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLokasiTable extends Migration
{
    public function up()
    {
        Schema::create('lokasi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_jalan')->unique();
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->string('latlng')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
