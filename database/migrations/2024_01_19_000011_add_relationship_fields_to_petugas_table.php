<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToPetugasTable extends Migration
{
    public function up()
    {
        Schema::table('petugas', function (Blueprint $table) {
            $table->unsignedBigInteger('lokasi_id')->nullable();
            $table->foreign('lokasi_id', 'lokasi_fk_9453245')->references('id')->on('lokasi');
        });
    }
}
