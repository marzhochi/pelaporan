<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToTugarsTable extends Migration
{
    public function up()
    {
        Schema::table('tugars', function (Blueprint $table) {
            $table->unsignedBigInteger('petugas_id')->nullable();
            $table->foreign('petugas_id', 'petugas_fk_9410027')->references('id')->on('users');
            $table->unsignedBigInteger('pengaduan_id')->nullable();
            $table->foreign('pengaduan_id', 'pengaduan_fk_9410028')->references('id')->on('pengaduans');
            $table->unsignedBigInteger('kategori_id')->nullable();
            $table->foreign('kategori_id', 'kategori_fk_9410029')->references('id')->on('kategoris');
        });
    }
}
