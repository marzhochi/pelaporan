<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToLaporanTable extends Migration
{
    public function up()
    {
        Schema::table('laporan', function (Blueprint $table) {
            $table->unsignedBigInteger('penugasan_id')->nullable();
            $table->foreign('penugasan_id', 'penugasan_fk_9457228')->references('id')->on('penugasan');
            $table->unsignedBigInteger('tugas_id')->nullable();
            $table->foreign('tugas_id', 'tugas_fk_9410040')->references('id')->on('tugas');
            $table->unsignedBigInteger('petugas_id')->nullable();
            $table->foreign('petugas_id', 'petugas_fk_9509962')->references('id')->on('petugas');
        });
    }
}
