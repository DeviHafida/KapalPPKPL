<?php

namespace App\Http\Controllers;

use App\Models\AreaInspeksi;
use App\Models\Kapal;
use App\Models\MetodeNdt;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TestSelectionController extends Controller
{
    // Available test types with implementation status
    private const AVAILABLE_TEST_TYPES = [
        'ultrasonic' => 'Ultrasonic Test (UT)',
    ];

    public function index(): View
    {
        // Get ship types from database - distinct jenis kapal
        $shipTypesDb = Kapal::where('status_operasional', 'Aktif')
            ->distinct('jenis_kapal')
            ->pluck('jenis_kapal')
            ->mapWithKeys(fn($item) => [strtolower(str_replace(' ', '_', $item)) => $item])
            ->toArray();

        // Get ship areas from database - distinct area names, limit to 5
        $shipAreasDb = AreaInspeksi::distinct('nama_area')
            ->pluck('id_area', 'nama_area')
            ->mapWithKeys(fn($id, $nama) => [strtolower(str_replace(' ', '_', $nama)) => $nama])
            ->slice(0, 5)
            ->toArray();

        // Get test types from database
        $testTypesFromDb = MetodeNdt::all()->pluck('nama_metode', 'kode_metode')->toArray();

        return view('home', [
            'shipTypes' => $shipTypesDb,
            'shipAreas' => $shipAreasDb,
            'testTypes' => $testTypesFromDb,
        ]);
    }

    public function selectTest(): RedirectResponse
    {
        // Get form inputs from request body
        $shipType = request('ship_type');
        $testType = request('test_type');
        $shipArea = request('ship_area');

        // Validate ship type exists in database
        $kapal = Kapal::where('jenis_kapal', $this->normalizeShipType($shipType))
            ->where('status_operasional', 'Aktif')
            ->first();
        
        if (!$kapal) {
            return redirect()->route('home')->with('error', 'Pilihan jenis kapal tidak valid. Silakan coba lagi.');
        }

        // Validate ship area exists in database
        $area = AreaInspeksi::where('nama_area', $this->denormalizeAreaName($shipArea))->first();
        if (!$area) {
            return redirect()->route('home')->with('error', 'Pilihan area kapal tidak valid. Silakan coba lagi.');
        }

        // Validate test type exists in database
        $metode = MetodeNdt::where('kode_metode', $testType)->first();
        if (!$metode) {
            return redirect()->route('home')->with('error', 'Jenis pengujian yang dipilih tidak ditemukan. Silakan coba lagi.');
        }

        // Check if test type is available for use
        if (!array_key_exists($testType, self::AVAILABLE_TEST_TYPES)) {
            return redirect()->route('home')->with('info', $metode->nama_metode . ' sedang dalam pengembangan. Fitur ini akan tersedia segera. Silakan pilih jenis pengujian lain untuk dilanjutkan.');
        }

        // Generate inspection ID (simplified - in real app use database)
        $idInspeksi = now()->timestamp;

        // Route to appropriate test page
        if ($testType === 'ultrasonic') {
            return redirect()->route('ultrasonic.create', [
                'idInspeksi' => $idInspeksi,
                'shipType' => $shipType,
                'shipArea' => $area->nama_area,
            ]);
        }

        // Fallback (should never reach here with current logic)
        return redirect()->route('home')->with('error', 'Terjadi kesalahan saat memproses pilihan Anda. Silakan coba lagi.');
    }

    /**
     * Normalize ship type from database format to form format
     */
    private function normalizeShipType(string $type): string
    {
        $map = [
            'tanker' => 'Tanker',
            'bulk_carrier' => 'Bulk Carrier',
            'container_ship' => 'Container Ship',
            'general_cargo' => 'General Cargo',
        ];
        return $map[$type] ?? ucfirst(str_replace('_', ' ', $type));
    }

    /**
     * Denormalize area name from form format to database format
     */
    private function denormalizeAreaName(string $area): string
    {
        return str_replace('_', ' ', ucwords(str_replace('_', ' ', $area), ' '));
    }
}
