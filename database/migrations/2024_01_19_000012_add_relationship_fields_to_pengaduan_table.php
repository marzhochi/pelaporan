<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToPengaduanTable extends Migration
{
    public function up()
    {
        Schema::table('pengaduan', function (Blueprint $table) {
            $table->unsignedBigInteger('lokasi_id')->nullable();
            $table->foreign('lokasi_id', 'lokasi_fk_9409984')->references('id')->on('lokasi');
        });
    }
}
