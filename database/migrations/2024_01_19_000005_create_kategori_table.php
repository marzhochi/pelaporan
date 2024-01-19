<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKategoriTable extends Migration
{
    public function up()
    {
        Schema::create('kategori', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_kategori');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
