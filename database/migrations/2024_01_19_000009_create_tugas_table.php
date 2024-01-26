<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTugasTable extends Migration
{
    public function up()
    {
        Schema::create('tugas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kategori');
            $table->string('judul_tugas');
            $table->string('keterangan')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
