<?php

namespace Database\Seeders;

use App\Models\MetodeNdt;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MetodeNdtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $metodeData = [
            [
                'kode_metode' => 'ultrasonic',
                'nama_metode' => 'Ultrasonic Test (UT)',
                'deskripsi' => 'Pengujian menggunakan gelombang ultrasonic untuk mendeteksi cacat internal pada material.',
            ],
            [
                'kode_metode' => 'penetrant',
                'nama_metode' => 'Penetrant Test (PT)',
                'deskripsi' => 'Pengujian menggunakan cairan penetrant untuk mendeteksi cacat permukaan pada material.',
            ],
            [
                'kode_metode' => 'vacuum',
                'nama_metode' => 'Vacuum Test',
                'deskripsi' => 'Pengujian menggunakan teknik vakum untuk mendeteksi kebocoran pada sistem tertutup.',
            ],
            [
                'kode_metode' => 'metalografi',
                'nama_metode' => 'Metalografi Test',
                'deskripsi' => 'Analisis struktur mikroskopis material untuk mengevaluasi sifat mekanik dan kualitas material.',
            ],
        ];

        foreach ($metodeData as $metode) {
            MetodeNdt::create($metode);
        }
    }
}
