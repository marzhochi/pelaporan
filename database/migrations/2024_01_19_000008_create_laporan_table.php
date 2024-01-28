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
            $table->double('jarak')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
