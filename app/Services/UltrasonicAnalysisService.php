<?php

namespace App\Services;

class UltrasonicAnalysisService
{
    /**
     * Aturan batas penipisan berdasarkan Biro Klasifikasi (BKI/DNV)
     * - OK: penipisan < 10%
     * - Repair: penipisan 10% - 25%
     * - Renew: penipisan > 25%
     */
    const LIMIT_OK = 10;      // 10%
    const LIMIT_REPAIR = 25;  // 25%
    
    /**
     * Standar akseptansi cacat las berdasarkan AWS D1.1
     * - Accepted: kedalaman < 3mm DAN panjang < 25mm
     * - Rejected: kedalaman >= 3mm ATAU panjang >= 25mm
     */
    const MAX_CRACK_DEPTH = 3;   // mm
    const MAX_CRACK_LENGTH = 25;  // mm
    
    /**
     * Klasifikasi jenis cacat ultrasonic
     */
    const DEFECT_TYPES = [
        'porosity' => 'Porosity (Rongga Gas)',
        'slag_inclusion' => 'Slag Inclusion (Terak Terperangkap)',
        'lack_of_fusion' => 'Lack of Fusion (Kurang Fusi)',
        'crack' => 'Crack (Retakan)',
        'undercut' => 'Undercut (Takik Las)'
    ];
    
    /**
     * Analisis Ketebalan (Thickness Analysis)
     * Menghitung penipisan dan menentukan status
     */
    public function analyzeThickness($t_origin, $current_thickness, $method = 'rule_90')
    {
        if ($t_origin <= 0) {
            return [
                'error' => 'Ketebalan desain awal tidak valid'
            ];
        }
        
        // Hitung persentase penipisan
        $reduction = $t_origin - $current_thickness;
        $percentage = ($reduction / $t_origin) * 100;
        $percentage = round($percentage, 2);
        
        // Tentukan status berdasarkan persentase penipisan
        if ($percentage < self::LIMIT_OK) {
            $status = 'OK';
            $status_desc = 'Plat dalam kondisi baik, aman untuk operasional';
            $action = 'Lanjutkan pemantauan rutin';
        } elseif ($percentage >= self::LIMIT_OK && $percentage < self::LIMIT_REPAIR) {
            $status = 'REPAIR';
            $status_desc = 'Plat mengalami penipisan signifikan, perlu perbaikan';
            $action = 'Lakukan repair atau penguatan plat';
        } else {
            $status = 'RENEW';
            $status_desc = 'Plat sudah sangat menipis, tidak aman';
            $action = 'Ganti plat baru (renew) segera';
        }
        
        // Hitung batas standar berdasarkan metode
        $standard_limit = ($method == 'rule_90') ? $t_origin * 0.9 : $t_origin * 0.85;
        
        return [
            't_origin' => $t_origin,
            'current_thickness' => $current_thickness,
            'reduction' => round($reduction, 2),
            'percentage' => $percentage,
            'status' => $status,
            'status_desc' => $status_desc,
            'action' => $action,
            'standard_limit' => round($standard_limit, 2),
            'is_safe' => ($percentage < self::LIMIT_OK),
            'method_used' => $method
        ];
    }
    
    /**
     * Analisis Cacat (Defect Analysis)
     * Mengklasifikasikan cacat dan menentukan akseptansi
     */
    public function analyzeDefect($defect_type, $depth_mm, $length_mm, $area_class = 'B')
    {
        $result = [];
        
        // 1. Klasifikasi Cacat
        $defect_key = strtolower(str_replace(' ', '_', $defect_type));
        $classification = self::DEFECT_TYPES[$defect_key] ?? $defect_type;
        
        $result['classification'] = $classification;
        $result['depth'] = $depth_mm;
        $result['length'] = $length_mm;
        $result['area_class'] = $area_class;
        
        // 2. Penentuan Akseptansi berdasarkan standar
        $is_depth_acceptable = ($depth_mm < self::MAX_CRACK_DEPTH);
        $is_length_acceptable = ($length_mm < self::MAX_CRACK_LENGTH);
        
        if ($is_depth_acceptable && $is_length_acceptable) {
            $result['acceptance_status'] = 'ACCEPTED';
            $result['acceptance_desc'] = 'Cacat masih dalam batas toleransi, aman digunakan';
            $result['recommendation'] = 'Catat dalam laporan, lanjutkan operasional';
        } else {
            $result['acceptance_status'] = 'REJECTED';
            $result['acceptance_desc'] = 'Cacat melebihi batas toleransi, tidak aman';
            $result['recommendation'] = 'Lakukan perbaikan sebelum operasional';
            
            // Berikan alasan spesifik
            if (!$is_depth_acceptable && !$is_length_acceptable) {
                $result['rejection_reason'] = "Kedalaman ({$depth_mm}mm) dan panjang ({$length_mm}mm) melebihi standar";
            } elseif (!$is_depth_acceptable) {
                $result['rejection_reason'] = "Kedalaman cacat ({$depth_mm}mm) melebihi batas maksimal " . self::MAX_CRACK_DEPTH . "mm";
            } else {
                $result['rejection_reason'] = "Panjang cacat ({$length_mm}mm) melebihi batas maksimal " . self::MAX_CRACK_LENGTH . "mm";
            }
        }
        
        // 3. Severity Level
        if ($depth_mm < 1) {
            $result['severity'] = 'Low';
        } elseif ($depth_mm < 3) {
            $result['severity'] = 'Medium';
        } else {
            $result['severity'] = 'High';
        }
        
        return $result;
    }
    
    /**
     * Analisis Lengkap (Ketebalan + Cacat)
     */
    public function fullAnalysis($data)
    {
        $analysis = [
            'thickness_analysis' => $this->analyzeThickness(
                $data['t_origin'],
                $data['nilai_ketebalan'],
                $data['metode_perhitungan'] ?? 'rule_90'
            ),
            'defect_analysis' => null,
            'final_verdict' => [],
            'summary' => ''
        ];
        
        // Jika ada data cacat
        if (!empty($data['jenis_cacat']) && !empty($data['kedalaman_cacat'])) {
            $analysis['defect_analysis'] = $this->analyzeDefect(
                $data['jenis_cacat'],
                $data['kedalaman_cacat'],
                $data['panjang_cacat'] ?? 0,
                $data['kelas_area'] ?? 'B'
            );
        }
        
        // Final Verdict (gabungan ketebalan dan cacat)
        $thickness_status = $analysis['thickness_analysis']['status'];
        $defect_status = $analysis['defect_analysis']['acceptance_status'] ?? null;
        
        if ($thickness_status == 'OK' && ($defect_status == 'ACCEPTED' || $defect_status == null)) {
            $analysis['final_verdict'] = [
                'status' => 'PASS',
                'color' => 'green',
                'message' => 'Inspeksi LULUS. Kapal aman untuk beroperasi.'
            ];
        } elseif ($thickness_status == 'REPAIR' && $defect_status != 'REJECTED') {
            $analysis['final_verdict'] = [
                'status' => 'CONDITIONAL',
                'color' => 'yellow',
                'message' => 'Inspeksi DITERIMA DENGAN CATATAN. Perlu perbaikan terjadwal.'
            ];
        } else {
            $analysis['final_verdict'] = [
                'status' => 'FAIL',
                'color' => 'red',
                'message' => 'Inspeksi TIDAK LULUS. Kapal tidak aman, perbaikan wajib dilakukan.'
            ];
        }
        
        // Ringkasan singkat
        $analysis['summary'] = $this->generateSummary($analysis);
        
        return $analysis;
    }
    
    /**
     * Generate ringkasan hasil analisis
     */
    private function generateSummary($analysis)
    {
        $th = $analysis['thickness_analysis'];
        $summary = "📊 ANALISIS KETEBALAN: {$th['percentage']}% penipisan → STATUS: {$th['status']}. ";
        
        if ($analysis['defect_analysis']) {
            $df = $analysis['defect_analysis'];
            $summary .= "🔍 ANALISIS CACAT: {$df['classification']} (kedalaman {$df['depth']}mm) → STATUS: {$df['acceptance_status']}. ";
        }
        
        $summary .= $analysis['final_verdict']['message'];
        
        return $summary;
    }
}