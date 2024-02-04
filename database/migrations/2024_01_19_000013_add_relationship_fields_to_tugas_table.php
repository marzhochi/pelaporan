<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToTugasTable extends Migration
{
    public function up()
    {
        Schema::table('tugas', function (Blueprint $table) {
            $table->unsignedBigInteger('jenis_id')->nullable();
            $table->foreign('jenis_id', 'jenis_fk_9453285')->references('id')->on('jenis_tugas');
            $table->unsignedBigInteger('lokasi_id')->nullable();
            $table->foreign('lokasi_id', 'lokasi_fk_9453286')->references('id')->on('lokasi');
        });
    }
}
