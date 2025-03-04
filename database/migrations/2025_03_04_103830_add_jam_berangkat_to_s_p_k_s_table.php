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
        Schema::table('s_p_k_s', function (Blueprint $table) {
            $table->string('tanggal_keberangkatan')->nullable();
            $table->string('jam_keberangkatan')->nullable();
            $table->string('tanggal_kepulangan')->nullable();
            $table->string('jam_kepulangan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('s_p_k_s', function (Blueprint $table) {
            $table->dropColumn('tanggal_keberangkatan');
            $table->dropColumn('jam_keberangkatan');
            $table->dropColumn('tanggal_kepulangan');
            $table->dropColumn('jam_kepulangan');
        });
    }
};
