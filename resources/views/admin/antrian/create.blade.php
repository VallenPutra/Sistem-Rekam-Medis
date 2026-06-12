@extends('layouts.app')
@section('title', 'Daftarkan Pasien')
@section('page-title', 'Pendaftaran Pasien')

@section('content')
<div class="row justify-content-center">
<div class="col-md-8">
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><strong>Form Pendaftaran & Antrian</strong></div>
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $e) <div>{{ $e }}</div> @endforeach
        </div>
        @endif
        <form action="{{ route('admin.antrian.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Pasien</label>
                    <select name="pasien_id" class="form-select" required>
                        <option value="">-- Pilih Pasien --</option>
                        @foreach($pasienList as $p)
                            <option value="{{ $p->id }}" {{ old('pasien_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->no_rm }} - {{ $p->nama }}
                            </option>
                        @endforeach
                    </select>
                    <small><a href="{{ route('admin.pasien.create') }}" target="_blank">+ Daftarkan pasien baru</a></small>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Dokter</label>
                    <select name="dokter_id" class="form-select" required>
                        <option value="">-- Pilih Dokter --</option>
                        @foreach($dokterList as $d)
                            <option value="{{ $d->id }}">{{ $d->name }} {{ $d->spesialis ? "({$d->spesialis})" : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Keluhan Utama</label>
                    <input type="text" name="keluhan_utama" class="form-control" required value="{{ old('keluhan_utama') }}" placeholder="Keluhan yang dibawa pasien...">
                </div>

                <div class="col-12"><hr><strong>Tanda Vital Awal</strong></div>
                <div class="col-md-3">
                    <label class="form-label">Berat Badan (kg)</label>
                    <input type="number" step="0.1" name="berat_badan" class="form-control" value="{{ old('berat_badan') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tinggi Badan (cm)</label>
                    <input type="number" step="0.1" name="tinggi_badan" class="form-control" value="{{ old('tinggi_badan') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tekanan Darah</label>
                    <input type="text" name="tekanan_darah" class="form-control" placeholder="120/80" value="{{ old('tekanan_darah') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Suhu Tubuh (°C)</label>
                    <input type="number" step="0.1" name="suhu_tubuh" class="form-control" value="{{ old('suhu_tubuh') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Nadi (/menit)</label>
                    <input type="number" name="nadi" class="form-control" value="{{ old('nadi') }}">
                </div>

                <div class="col-12 mt-2">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check me-1"></i>Daftarkan ke Antrian</button>
                    <a href="{{ route('admin.antrian.index') }}" class="btn btn-secondary ms-2">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
