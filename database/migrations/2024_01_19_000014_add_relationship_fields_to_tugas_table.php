<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToTugasTable extends Migration
{
    public function up()
    {
        Schema::table('tugas', function (Blueprint $table) {
            $table->unsignedBigInteger('petugas_id')->nullable();
            $table->foreign('petugas_id', 'petugas_fk_9410027')->references('id')->on('pengguna');
            $table->unsignedBigInteger('pengaduan_id')->nullable();
            $table->foreign('pengaduan_id', 'pengaduan_fk_9410028')->references('id')->on('pengaduan');
            $table->unsignedBigInteger('jenis_id')->nullable();
            $table->foreign('jenis_id', 'jenis_fk_9410029')->references('id')->on('jenis');
            $table->unsignedBigInteger('lokasi_id')->nullable();
            $table->foreign('lokasi_id', 'lokasi_fk_9424581')->references('id')->on('lokasi');
        });
    }
}
