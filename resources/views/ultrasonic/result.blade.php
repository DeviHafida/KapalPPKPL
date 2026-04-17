@extends('layouts.app')

@section('title', 'Hasil Analisis Ultrasonic Test')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h1 class="h5 mb-0">📊 Hasil Analisis Ultrasonic Test</h1>
                </div>
                <div class="card-body">
                    <!-- Informasi Inspeksi (sama seperti di form) -->
                    <div class="row mb-4 pb-3 border-bottom">
                        <div class="col-md-6">
                            <p class="mb-1"><small class="text-muted">ID Inspeksi</small></p>
                            <p class="mb-0"><strong>{{ $inspeksi->id_inspeksi }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><small class="text-muted">Jenis Kapal</small></p>
                            <p class="mb-0"><strong>{{ $inspeksi->jenis_kapal }}</strong></p>
                        </div>
                        <div class="col-md-6 mt-2">
                            <p class="mb-1"><small class="text-muted">Area Kapal</small></p>
                            <p class="mb-0"><strong>{{ $inspeksi->area_kapal }}</strong></p>
                        </div>
                        <div class="col-md-6 mt-2">
                            <p class="mb-1"><small class="text-muted">Metode Perhitungan</small></p>
                            <p class="mb-0"><strong>{{ $inspeksi->metode_perhitungan == 'rule_90' ? 'Rule 90% (0.9 x t_origin)' : 'Rule 85% (0.85 x t_origin)' }}</strong></p>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <!-- Hasil Analisis Ketebalan -->
                    <div class="card mb-3 border-0 shadow-none">
                        <div class="card-header bg-success text-white py-2">
                            <h6 class="mb-0">📏 Analisis Ketebalan</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td width="45%"><small class="text-muted">Ketebalan Desain Awal (t_origin)</small></td>
                                    <td width="10%">:</td>
                                    <td><strong>{{ $inspeksi->t_origin }} mm</strong></td>
                                </tr>
                                <tr>
                                    <td><small class="text-muted">Nilai Ketebalan Hasil Ukur</small></td>
                                    <td>:</td>
                                    <td><strong>{{ $inspeksi->nilai_ketebalan }} mm</strong></td>
                                </tr>
                                <tr>
                                    <td><small class="text-muted">Batas Standar Minimum</small></td>
                                    <td>:</td>
                                    <td><strong>{{ $inspeksi->batas_standar }} mm</strong></td>
                                </tr>
                                <tr>
                                    <td><small class="text-muted">Pengurangan / Penipisan</small></td>
                                    <td>:</td>
                                    <td>
                                        <strong class="text-danger">{{ number_format($inspeksi->t_origin - $inspeksi->nilai_ketebalan, 2) }} mm</strong>
                                        <span class="badge {{ $inspeksi->persentase_penipisan > 25 ? 'bg-danger' : ($inspeksi->persentase_penipisan > 10 ? 'bg-warning' : 'bg-success') }} ms-2">
                                            {{ $inspeksi->persentase_penipisan }}%
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><small class="text-muted">Status Ketebalan</small></td>
                                    <td>:</td>
                                    <td>
                                        <span class="badge {{ $inspeksi->status_ketebalan == 'OK' ? 'bg-success' : ($inspeksi->status_ketebalan == 'REPAIR' ? 'bg-warning' : 'bg-danger') }} fs-6">
                                            {{ $inspeksi->status_ketebalan }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                            
                            <div class="alert alert-info mt-3 mb-0 py-2">
                                <small>
                                    📌 
                                    @if($inspeksi->status_ketebalan == 'OK')
                                        Plat dalam kondisi baik, aman untuk operasional.
                                    @elseif($inspeksi->status_ketebalan == 'REPAIR')
                                        Plat mengalami penipisan signifikan, perlu perbaikan.
                                    @else
                                        Plat sudah sangat menipis, tidak aman. WAJIB GANTI PLAT!
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Hasil Analisis Cacat (jika ada) -->
                    @if($inspeksi->jenis_cacat && $inspeksi->jenis_cacat != '')
                    <div class="card mb-3 border-0 shadow-none">
                        <div class="card-header bg-warning text-dark py-2">
                            <h6 class="mb-0">🔍 Analisis Cacat</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td width="45%"><small class="text-muted">Jenis Cacat</small></td>
                                    <td width="10%">:</td>
                                    <td><strong>{{ $inspeksi->jenis_cacat }}</strong></td>
                                </tr>
                                <tr>
                                    <td><small class="text-muted">Klasifikasi</small></td>
                                    <td>:</td>
                                    <td><strong>{{ $inspeksi->klasifikasi_cacat ?? 'Belum diklasifikasi' }}</strong></td>
                                </tr>
                                <tr>
                                    <td><small class="text-muted">Kedalaman Cacat</small></td>
                                    <td>:</td>
                                    <td><strong>{{ $inspeksi->kedalaman_cacat }} mm</strong></td>
                                </tr>
                                <tr>
                                    <td><small class="text-muted">Panjang Cacat</small></td>
                                    <td>:</td>
                                    <td><strong>{{ $inspeksi->panjang_cacat }} mm</strong></td>
                                </tr>
                                <tr>
                                    <td><small class="text-muted">Status Akseptansi</small></td>
                                    <td>:</td>
                                    <td>
                                        <span class="badge {{ $inspeksi->status_akseptansi == 'ACCEPTED' ? 'bg-success' : 'bg-danger' }} fs-6">
                                            {{ $inspeksi->status_akseptansi ?? 'Belum ditentukan' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                            
                            <div class="alert alert-secondary mt-3 mb-0 py-2">
                                <small>
                                    🎯 Rekomendasi: 
                                    @if($inspeksi->status_akseptansi == 'ACCEPTED')
                                        Cacat masih dalam batas toleransi, aman digunakan.
                                    @elseif($inspeksi->status_akseptansi == 'REJECTED')
                                        Cacat melebihi batas toleransi, WAJIB DIPERBAIKI sebelum operasional!
                                    @else
                                        Tidak ada cacat signifikan yang terdeteksi.
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- FINAL VERDICT -->
                    <div class="card mb-4 border-0 shadow-none">
                        <div class="card-header bg-dark text-white py-2">
                            <h6 class="mb-0">⚖️ FINAL VERDICT</h6>
                        </div>
                        <div class="card-body text-center">
                            @php
                                $finalStatus = 'PASS';
                                $finalColor = 'success';
                                $finalMessage = 'Inspeksi LULUS. Kapal aman untuk beroperasi.';
                                
                                if($inspeksi->status_ketebalan == 'REPAIR') {
                                    $finalStatus = 'CONDITIONAL';
                                    $finalColor = 'warning';
                                    $finalMessage = 'Inspeksi DITERIMA DENGAN CATATAN. Perlu perbaikan terjadwal.';
                                } elseif($inspeksi->status_ketebalan == 'RENEW' || $inspeksi->status_akseptansi == 'REJECTED') {
                                    $finalStatus = 'FAIL';
                                    $finalColor = 'danger';
                                    $finalMessage = 'Inspeksi TIDAK LULUS. Kapal tidak aman, perbaikan wajib dilakukan.';
                                }
                            @endphp
                            
                            <span class="display-6 fw-bold text-{{ $finalColor }}">
                                {{ $finalStatus }}
                            </span>
                            <p class="mt-3 mb-0">{{ $finalMessage }}</p>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('ultrasonic.create', $inspeksi->id_inspeksi) }}" class="btn btn-warning">
                            ✏️ Edit Data
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-secondary">
                            🏠 Kembali ke Home
                        </a>
                        <a href="{{ route('ultrasonic.analysis.index') }}" class="btn btn-primary">
                            🔬 Inspeksi Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection