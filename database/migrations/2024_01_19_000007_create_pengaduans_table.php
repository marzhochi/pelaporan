<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengaduansTable extends Migration
{
    public function up()
    {
        Schema::create('pengaduans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_lengkap');
            $table->string('no_telepon');
            $table->string('judul_pengaduan');
            $table->string('keterangan')->nullable();
            $table->string('latlang');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
