<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InspeksiUltrasonic extends Model
{
    protected $table = 'inspeksi_ultrasonic';
    
    protected $fillable = [
        'id_inspeksi',
        'jenis_kapal',
        'area_kapal',
        't_origin',
        'nilai_ketebalan',
        'batas_standar',
        'metode_perhitungan',
        'frekuensi_ut',
        'kelas_area',
        'jenis_cacat',
        'kedalaman_cacat',
        'panjang_cacat',
        'echo_amplitude',
        'persentase_penipisan',
        'status_ketebalan',
        'klasifikasi_cacat',
        'status_akseptansi'
    ];
}