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
        Schema::create('area_inspeksi', function (Blueprint $table) {
            $table->id('id_area');
            $table->unsignedBigInteger('id_kapal');
            $table->string('nama_area', 100);
            $table->string('kode_area', 20)->unique();
            $table->string('titik_koordinat', 50)->nullable();
            $table->timestamps();

            $table->foreign('id_kapal')->references('id_kapal')->on('kapal')->onDelete('cascade');
            $table->index('id_kapal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('area_inspeksi');
    }
};
