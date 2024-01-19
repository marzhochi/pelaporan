<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTugarsTable extends Migration
{
    public function up()
    {
        Schema::create('tugars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('judul_tugas');
            $table->string('keterangan')->nullable();
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
