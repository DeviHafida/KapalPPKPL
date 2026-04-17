@extends('layouts.app')

@section('title', 'Input Data Ultrasonic Test')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h1 class="h5 mb-0">Form Input Ultrasonic Test</h1>
                </div>
                <div class="card-body">
                    <div class="row mb-4 pb-3 border-bottom">
                        <div class="col-md-6">
                            <p class="mb-1"><small class="text-muted">ID Inspeksi</small></p>
                            <p class="mb-0"><strong>{{ $idInspeksi }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><small class="text-muted">Jenis Kapal</small></p>
                            <p class="mb-0"><strong>{{ $shipType }}</strong></p>
                        </div>
                        <div class="col-md-6 mt-2">
                            <p class="mb-1"><small class="text-muted">Area Kapal</small></p>
                            <p class="mb-0"><strong>{{ $shipArea }}</strong></p>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('ultrasonic.store', $idInspeksi) }}">
                        @csrf

                        <div class="mb-3">
                            <label for="t_origin" class="form-label">Ketebalan Desain Awal (t_origin, mm)</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="t_origin" name="t_origin" value="{{ old('t_origin') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="metode_t_min" class="form-label">Metode Perhitungan t_min</label>
                            <select class="form-select" id="metode_t_min" name="metode_t_min" required>
                                <option value="rule_90" @selected(old('metode_t_min') === 'rule_90')>Rule 90% (0.9 x t_origin)</option>
                                <option value="bki" @selected(old('metode_t_min') === 'bki')>BKI (t_origin - 0.5 x sqrt(t_origin))</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="nilai_ketebalan" class="form-label">Nilai Ketebalan</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="nilai_ketebalan" name="nilai_ketebalan" value="{{ old('nilai_ketebalan') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="batas_standar" class="form-label">Batas Standar</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="batas_standar" name="batas_standar" value="{{ old('batas_standar') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="frekuensi_ut" class="form-label">Frekuensi UT</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="frekuensi_ut" name="frekuensi_ut" value="{{ old('frekuensi_ut') }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="level_pengujian" class="form-label">Level Pengujian ISO 17640</label>
                                <select class="form-select" id="level_pengujian" name="level_pengujian" required>
                                    <option value="A" @selected(old('level_pengujian') === 'A')>A</option>
                                    <option value="B" @selected(old('level_pengujian', 'B') === 'B')>B</option>
                                    <option value="C" @selected(old('level_pengujian') === 'C')>C</option>
                                    <option value="D" @selected(old('level_pengujian') === 'D')>D</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="kelas_area" class="form-label">Kelas Area</label>
                                <select class="form-select" id="kelas_area" name="kelas_area" required>
                                    <option value="A" @selected(old('kelas_area') === 'A')>A (Kritis/Midship)</option>
                                    <option value="B" @selected(old('kelas_area', 'B') === 'B')>B (Non-Kritis)</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="jenis_cacat" class="form-label">Jenis Cacat</label>
                            <input type="text" class="form-control" id="jenis_cacat" name="jenis_cacat" value="{{ old('jenis_cacat') }}" maxlength="255" required>
                        </div>

                        <div class="mb-3">
                            <label for="kedalaman_cacat" class="form-label">Kedalaman Cacat</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="kedalaman_cacat" name="kedalaman_cacat" value="{{ old('kedalaman_cacat') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="panjang_cacat" class="form-label">Panjang Cacat (mm)</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="panjang_cacat" name="panjang_cacat" value="{{ old('panjang_cacat') }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="amplitudo_gema" class="form-label">Echo Amplitude</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="amplitudo_gema" name="amplitudo_gema" value="{{ old('amplitudo_gema') }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="dac_referensi" class="form-label">DAC Referensi</label>
                                <input type="number" step="0.01" min="0.01" class="form-control" id="dac_referensi" name="dac_referensi" value="{{ old('dac_referensi') }}" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Data</button>
                        <a href="{{ route('home') }}" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
