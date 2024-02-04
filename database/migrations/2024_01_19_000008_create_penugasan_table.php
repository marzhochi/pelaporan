<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenugasanTable extends Migration
{
    public function up()
    {
        Schema::create('penugasan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('judul_tugas');
            $table->string('keterangan')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
