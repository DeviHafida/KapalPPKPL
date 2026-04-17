<?php

namespace Database\Seeders;

use App\Models\Kapal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KapalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kapalData = [
            [
                'nama_kapal' => 'MV Sinar Jaya',
                'jenis_kapal' => 'Tanker',
                'tahun_pembuatan' => 2015,
                'bobot_kapal' => 45000.00,
                'status_operasional' => 'Aktif',
            ],
            [
                'nama_kapal' => 'MT Maren Pasifik',
                'jenis_kapal' => 'Bulk Carrier',
                'tahun_pembuatan' => 2018,
                'bobot_kapal' => 58000.00,
                'status_operasional' => 'Aktif',
            ],
            [
                'nama_kapal' => 'CMA CGM Semarang',
                'jenis_kapal' => 'Container Ship',
                'tahun_pembuatan' => 2016,
                'bobot_kapal' => 65000.00,
                'status_operasional' => 'Perbaikan',
            ],
            [
                'nama_kapal' => 'KM Dharma Bhakti',
                'jenis_kapal' => 'General Cargo',
                'tahun_pembuatan' => 2012,
                'bobot_kapal' => 35000.00,
                'status_operasional' => 'Aktif',
            ],
        ];

        foreach ($kapalData as $kapal) {
            Kapal::create($kapal);
        }
    }
}
