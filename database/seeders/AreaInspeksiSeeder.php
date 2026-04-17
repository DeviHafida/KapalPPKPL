<?php

namespace Database\Seeders;

use App\Models\AreaInspeksi;
use App\Models\Kapal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaInspeksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all kapal
        $kapal = Kapal::all();

        $areaData = [
            [
                'nama_area' => 'Lambung (Hull)',
                'kode_area' => 'HULL',
                'titik_koordinat' => 'Port Side',
            ],
            [
                'nama_area' => 'Geladak (Deck)',
                'kode_area' => 'DECK',
                'titik_koordinat' => 'Midship',
            ],
            [
                'nama_area' => 'Sekat (Bulkhead)',
                'kode_area' => 'BLK',
                'titik_koordinat' => 'Forward',
            ],
            [
                'nama_area' => 'Lunas (Keel)',
                'kode_area' => 'KEEL',
                'titik_koordinat' => 'Center Line',
            ],
            [
                'nama_area' => 'Gading-gading (Frame)',
                'kode_area' => 'FRAME',
                'titik_koordinat' => 'Starboard Side',
            ],
        ];

        // Insert area untuk setiap kapal
        foreach ($kapal as $k) {
            foreach ($areaData as $area) {
                AreaInspeksi::create([
                    'id_kapal' => $k->id_kapal,
                    'nama_area' => $area['nama_area'],
                    'kode_area' => $area['kode_area'] . '_' . $k->id_kapal,
                    'titik_koordinat' => $area['titik_koordinat'],
                ]);
            }
        }
    }
}
