<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToLaporansTable extends Migration
{
    public function up()
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->unsignedBigInteger('lokasi_id')->nullable();
            $table->foreign('lokasi_id', 'lokasi_fk_9409997')->references('id')->on('lokasis');
            $table->unsignedBigInteger('tugas_id')->nullable();
            $table->foreign('tugas_id', 'tugas_fk_9410040')->references('id')->on('tugars');
        });
    }
}
