<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUltrasonicTestRequest;
use App\Services\UltrasonicTestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Throwable;

class UltrasonicTestController extends Controller
{
    public function __construct(
        private readonly UltrasonicTestService $ultrasonicTestService
    ) {}

    /**
     * Show create form for ultrasonic test
     */
    public function create(int $idInspeksi): View
    {
        $shipType = request('shipType', 'unknown');
        $shipArea = request('shipArea', 'unknown');

        // Map ship types to labels
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
     * Store ultrasonic test data (Web response - redirect)
     */
    public function store(StoreUltrasonicTestRequest $request, int $idInspeksi): RedirectResponse
    {
        try {
            $this->ultrasonicTestService->store($idInspeksi, $request->validated());

            return redirect()
                ->route('ultrasonic.create', $idInspeksi)
                ->with('success', 'Data ultrasonic test berhasil disimpan.');
        } catch (Throwable $exception) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data ultrasonic test.');
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
