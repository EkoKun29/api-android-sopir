<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('s_p_k_jembers', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user')->nullable();
            $table->string('tanggal')->nullable();
            $table->string('nama_sales')->nullable();
            $table->string('tanggal_muat')->nullable();
            $table->string('hari_jam_keberangkatan')->nullable();
            $table->string('hari_Jam_kepulangan')->nullable();
            $table->string('tanggal_keberangkatan')->nullable();
            $table->string('jam_keberangkatan')->nullable();
            $table->string('tanggal_kepulangan')->nullable();
            $table->string('jam_kepulangan')->nullable();
            $table->string('sopir')->nullable();
            $table->string('rute')->nullable();
            $table->string('dropper')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_p_k_jembers');
    }
};
