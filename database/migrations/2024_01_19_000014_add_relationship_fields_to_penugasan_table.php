<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToPenugasanTable extends Migration
{
    public function up()
    {
        Schema::table('penugasan', function (Blueprint $table) {
            $table->unsignedBigInteger('petugas_id')->nullable();
            $table->foreign('petugas_id', 'petugas_fk_9453295')->references('id')->on('petugas');
            $table->unsignedBigInteger('pengaduan_id')->nullable();
            $table->foreign('pengaduan_id', 'pengaduan_fk_9453296')->references('id')->on('pengaduan');
        });
    }
}
