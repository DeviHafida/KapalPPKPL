<?php

namespace App\Http\Controllers;

use App\Models\InspeksiUltrasonic;
use App\Services\UltrasonicAnalysisService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UltrasonicAnalysisController extends Controller
{
    protected $analysisService;
    
    public function __construct(UltrasonicAnalysisService $analysisService)
    {
        $this->analysisService = $analysisService;
    }
    
    /**
     * Menampilkan halaman form input
     */
    public function index()
    {
        $id_inspeksi = date('Ymd') . rand(1000, 9999);
        return view('ultrasonic.analysis', compact('id_inspeksi'));
    }
    
    /**
     * Memproses analisis (AJAX / Real-time)
     */
    public function analyze(Request $request)
    {
        $request->validate([
            't_origin' => 'required|numeric|min:1',
            'nilai_ketebalan' => 'required|numeric|min:0',
            'metode_perhitungan' => 'required|in:rule_90,rule_85',
            'jenis_cacat' => 'nullable|string',
            'kedalaman_cacat' => 'nullable|numeric|min:0',
            'panjang_cacat' => 'nullable|numeric|min:0',
            'kelas_area' => 'nullable|string',
            'frekuensi_ut' => 'nullable|numeric',
            'echo_amplitude' => 'nullable|string'
        ]);
        
        $analysis = $this->analysisService->fullAnalysis($request->all());
        
        $t_origin = $request->t_origin;
        $method = $request->metode_perhitungan;
        $batas_standar = ($method == 'rule_90') ? $t_origin * 0.9 : $t_origin * 0.85;
        
        return response()->json([
            'success' => true,
            'analysis' => $analysis,
            'batas_standar' => round($batas_standar, 2),
            'persentase_penipisan' => $analysis['thickness_analysis']['percentage']
        ]);
    }
    
    /**
     * Menyimpan data inspeksi ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_inspeksi' => 'required|unique:inspeksi_ultrasonic',
            'jenis_kapal' => 'required|string',
            'area_kapal' => 'required|string',
            't_origin' => 'required|numeric|min:1',
            'nilai_ketebalan' => 'required|numeric|min:0',
            'metode_perhitungan' => 'required|string',
            'frekuensi_ut' => 'nullable|numeric',
            'kelas_area' => 'nullable|string',
            'jenis_cacat' => 'nullable|string',
            'kedalaman_cacat' => 'nullable|numeric',
            'panjang_cacat' => 'nullable|numeric',
            'echo_amplitude' => 'nullable|string'
        ]);
        
        $analysis = $this->analysisService->fullAnalysis($request->all());
        
        $t_origin = $request->t_origin;
        $method = $request->metode_perhitungan;
        $batas_standar = ($method == 'rule_90') ? $t_origin * 0.9 : $t_origin * 0.85;
        
        $defectAnalysis = $analysis['defect_analysis'] ?? null;
        
        $inspeksi = InspeksiUltrasonic::create([
            'id_inspeksi' => $request->id_inspeksi,
            'jenis_kapal' => $request->jenis_kapal,
            'area_kapal' => $request->area_kapal,
            't_origin' => $request->t_origin,
            'nilai_ketebalan' => $request->nilai_ketebalan,
            'batas_standar' => $batas_standar,
            'metode_perhitungan' => $request->metode_perhitungan,
            'frekuensi_ut' => $request->frekuensi_ut,
            'kelas_area' => $request->kelas_area,
            'jenis_cacat' => $request->jenis_cacat,
            'kedalaman_cacat' => $request->kedalaman_cacat ?? 0,
            'panjang_cacat' => $request->panjang_cacat ?? 0,
            'echo_amplitude' => $request->echo_amplitude,
            'persentase_penipisan' => $analysis['thickness_analysis']['percentage'],
            'status_ketebalan' => $analysis['thickness_analysis']['status'],
            'klasifikasi_cacat' => $defectAnalysis['classification'] ?? null,
            'status_akseptansi' => $defectAnalysis['acceptance_status'] ?? null
        ]);
        
        return redirect()->route('ultrasonic.analysis.result', ['id' => $inspeksi->id_inspeksi])
            ->with('success', 'Data inspeksi berhasil disimpan!');
    }

    /**
     * Menampilkan halaman hasil analisis
     */
    public function result($id)
    {
        // Cari data berdasarkan id_inspeksi
        $inspeksi = InspeksiUltrasonic::where('id_inspeksi', $id)->first();
        
        // Jika TIDAK ADA, buat data sementara untuk tampilan (DUMMY)
        if (!$inspeksi) {
            // Buat objek sementara (tidak disimpan ke DB)
            $inspeksi = new InspeksiUltrasonic();
            $inspeksi->id_inspeksi = $id;
            $inspeksi->jenis_kapal = 'Tanker';
            $inspeksi->area_kapal = 'Lambung';
            $inspeksi->t_origin = 20;
            $inspeksi->nilai_ketebalan = 18.5;
            $inspeksi->batas_standar = 18;
            $inspeksi->metode_perhitungan = 'rule_90';
            $inspeksi->persentase_penipisan = 7.5;
            $inspeksi->status_ketebalan = 'OK';
            $inspeksi->jenis_cacat = '';
            $inspeksi->kedalaman_cacat = 0;
            $inspeksi->panjang_cacat = 0;
            $inspeksi->status_akseptansi = 'ACCEPTED';
            $inspeksi->kelas_area = 'B';
            $inspeksi->frekuensi_ut = 5;
            $inspeksi->echo_amplitude = 'DAC Referensi';
        }
        
        $analysis = $this->analysisService->fullAnalysis([
            't_origin' => $inspeksi->t_origin,
            'nilai_ketebalan' => $inspeksi->nilai_ketebalan,
            'metode_perhitungan' => $inspeksi->metode_perhitungan,
            'jenis_cacat' => $inspeksi->jenis_cacat,
            'kedalaman_cacat' => $inspeksi->kedalaman_cacat,
            'panjang_cacat' => $inspeksi->panjang_cacat,
            'kelas_area' => $inspeksi->kelas_area ?? 'B',
        ]);
        
        return view('ultrasonic.result', compact('inspeksi', 'analysis'));
    }
}