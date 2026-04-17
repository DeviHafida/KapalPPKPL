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
        Schema::create('kapal', function (Blueprint $table) {
            $table->id('id_kapal');
            $table->string('nama_kapal', 100);
            $table->string('jenis_kapal', 50); // Tanker, Bulk Carrier, Container Ship, General Cargo
            $table->integer('tahun_pembuatan');
            $table->decimal('bobot_kapal', 12, 2); // dalam ton
            $table->enum('status_operasional', ['Aktif', 'Perbaikan', 'Nonaktif'])->default('Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kapal');
    }
};
