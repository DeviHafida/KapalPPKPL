<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inspeksi_ultrasonic', function (Blueprint $table) {
            $table->id();
            $table->string('id_inspeksi', 20)->unique();
            $table->string('jenis_kapal', 50);
            $table->string('area_kapal', 100);
            $table->decimal('t_origin', 10, 2)->comment('Ketebalan desain awal (mm)');
            $table->decimal('nilai_ketebalan', 10, 2)->comment('Nilai ketebalan hasil ukur (mm)');
            $table->decimal('batas_standar', 10, 2)->nullable();
            $table->string('metode_perhitungan', 20)->default('rule_90');
            $table->decimal('frekuensi_ut', 10, 2)->nullable();
            $table->string('kelas_area', 50)->nullable();
            $table->string('jenis_cacat', 100)->nullable();
            $table->decimal('kedalaman_cacat', 10, 2)->nullable();
            $table->decimal('panjang_cacat', 10, 2)->nullable();
            $table->string('echo_amplitude', 100)->nullable();
            
            // Hasil analisis otomatis
            $table->decimal('persentase_penipisan', 10, 2)->nullable();
            $table->string('status_ketebalan', 20)->nullable(); // OK, Repair, Renew
            $table->string('klasifikasi_cacat', 50)->nullable();
            $table->string('status_akseptansi', 20)->nullable(); // Accepted, Rejected
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inspeksi_ultrasonic');
    }
};