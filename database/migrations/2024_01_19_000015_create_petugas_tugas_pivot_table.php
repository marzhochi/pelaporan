<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetugasTugasPivotTable extends Migration
{
    public function up()
    {
        Schema::create('petugas_tugas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tugas_id');
            $table->foreign('tugas_id', 'tugas_id_fk_9453287')->references('id')->on('tugas')->onDelete('cascade');
            $table->unsignedBigInteger('petugas_id');
            $table->foreign('petugas_id', 'petugas_id_fk_9453287')->references('id')->on('petugas')->onDelete('cascade');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }
}
