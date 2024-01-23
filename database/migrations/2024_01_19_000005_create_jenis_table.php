<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJenisTable extends Migration
{
    public function up()
    {
        Schema::create('jenis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_jenis');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
