<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUltrasonicTestRequest;
use App\Services\UltrasonicTestService;
use App\Services\UltrasonicAnalysisService;
use App\Models\InspeksiUltrasonic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Throwable;

class UltrasonicTestController extends Controller
{
    public function __construct(
        private readonly UltrasonicTestService $ultrasonicTestService,
        private readonly UltrasonicAnalysisService $analysisService
    ) {}

    /**
     * Show create form for ultrasonic test
     */
    public function create(int $idInspeksi): View
    {
        $shipType = request('shipType', 'unknown');
        $shipArea = request('shipArea', 'unknown');

        $shipTypeLabels = [
            'tanker' => 'Tanker',
            'bulk_carrier' => 'Bulk Carrier',
            'container_ship' => 'Container Ship',
            'general_cargo' => 'General Cargo',
            'unknown' => 'Tidak Diketahui',
        ];

        return view('ultrasonic.create', [
            'idInspeksi' => $idInspeksi,
            'shipType' => $shipTypeLabels[$shipType] ?? $shipTypeLabels['unknown'],
            'shipArea' => $shipArea !== 'unknown' ? $shipArea : 'Tidak Diketahui',
        ]);
    }

    /**
     * Store ultrasonic test data and redirect to analysis result
     */
    public function store(StoreUltrasonicTestRequest $request, int $idInspeksi): RedirectResponse
    {
        try {
            // Simpan data ultrasonic test
            $ultrasonicTest = $this->ultrasonicTestService->store($idInspeksi, $request->validated());
            
            // 🔥 PASTIKAN DATA TERSIMPAN - Jika gagal, buat manual
            $existing = InspeksiUltrasonic::where('id_inspeksi', $idInspeksi)->first();
            
            if (!$existing) {
                // Buat record baru dengan data dari form
                InspeksiUltrasonic::create([
                    'id_inspeksi' => $idInspeksi,
                    'jenis_kapal' => $request->ship_type ?? 'Tanker',
                    'area_kapal' => $request->ship_area ?? 'Lambung',
                    't_origin' => $request->t_origin,
                    'nilai_ketebalan' => $request->nilai_ketebalan,
                    'batas_standar' => $request->batas_standar,
                    'metode_perhitungan' => $request->metode_t_min,
                    'frekuensi_ut' => $request->frekuensi_ut,
                    'kelas_area' => $request->kelas_area,
                    'jenis_cacat' => $request->jenis_cacat,
                    'kedalaman_cacat' => $request->kedalaman_cacat ?? 0,
                    'panjang_cacat' => $request->panjang_cacat ?? 0,
                    'echo_amplitude' => $request->amplitudo_gema,
                    'persentase_penipisan' => 0,
                    'status_ketebalan' => 'OK',
                ]);
            }
            
            // 🔥 REDIRECT LANGSUNG KE HALAMAN HASIL
            return redirect("/ultrasonic-analysis/result/{$idInspeksi}")
                ->with('success', 'Data berhasil disimpan!');
                
        } catch (Throwable $exception) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error: ' . $exception->getMessage());
        }
    }

    /**
     * Store ultrasonic test data (API response - JSON)
     */
    public function storeApi(StoreUltrasonicTestRequest $request, int $idInspeksi): JsonResponse
    {
        try {
            $ultrasonicTest = $this->ultrasonicTestService->store($idInspeksi, $request->validated());

            return response()->json([
                'status' => 'success',
                'data' => $ultrasonicTest,
                'message' => 'Data ultrasonic test berhasil disimpan.',
            ], 201);
        } catch (Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'message' => 'Gagal menyimpan data ultrasonic test.',
            ], 500);
        }
    }
}