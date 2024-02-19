<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenugasanPetugasPivotTable extends Migration
{
    public function up()
    {
        Schema::create('penugasan_petugas', function (Blueprint $table) {
            $table->unsignedBigInteger('penugasan_id');
            $table->foreign('penugasan_id', 'penugasan_id_fk_9509970')->references('id')->on('penugasan')->onDelete('cascade');
            $table->unsignedBigInteger('petugas_id');
            $table->foreign('petugas_id', 'petugas_id_fk_9509970')->references('id')->on('petugas')->onDelete('cascade');
            $table->tinyInteger('status')->default(0);
        });
    }
}
