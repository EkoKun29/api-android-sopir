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
        Schema::create('absen_berangkats', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->string('nama')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('face')->nullable();
            $table->string('tanggal')->nullable();
            $table->string('jam')->nullable();
            $table->decimal('latitude', 18, 15)->nullable(); // Kolom latitude
            $table->decimal('longitude', 18, 15)->nullable();
            $table->string('lokasi')->nullable();
            $table->uuid('uuid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absen_berangkats');
    }
};
