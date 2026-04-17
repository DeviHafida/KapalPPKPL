@extends('layouts.app')

@section('title', 'Sistem Inspeksi Kapal NDT - Halaman Awal')

@section('content')
    <div class="d-flex align-items-center justify-content-center" style="min-height: 80vh;">
        <div class="row justify-content-center w-100">
            <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h1 class="h5 mb-0">Sistem Inspeksi Kapal NDT</h1>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">Silakan pilih jenis kapal, area kapal, dan jenis pengujian yang akan dilakukan</p>

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Terjadi Kesalahan!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <strong>Informasi:</strong> {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('test.select') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="ship_type" class="form-label">Jenis Kapal</label>
                            <select class="form-select @error('ship_type') is-invalid @enderror" id="ship_type" name="ship_type" required>
                                <option value="" selected disabled>-- Pilih Jenis Kapal --</option>
                                @foreach ($shipTypes as $key => $label)
                                    <option value="{{ $key }}" @selected(old('ship_type') === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('ship_type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="ship_area" class="form-label">Area Kapal</label>
                            <select class="form-select @error('ship_area') is-invalid @enderror" id="ship_area" name="ship_area" required>
                                <option value="" selected disabled>-- Pilih Area Kapal --</option>
                                @foreach ($shipAreas as $key => $label)
                                    <option value="{{ $key }}" @selected(old('ship_area') === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('ship_area')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="test_type" class="form-label">Jenis Pengujian</label>
                            <select class="form-select @error('test_type') is-invalid @enderror" id="test_type" name="test_type" required>
                                <option value="" selected disabled>-- Pilih Jenis Pengujian --</option>
                                @foreach ($testTypes as $key => $label)
                                    <option value="{{ $key }}" @selected(old('test_type') === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('test_type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">Mulai Pengujian</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
