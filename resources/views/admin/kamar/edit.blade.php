@extends('layouts.app')
@section('title', 'Edit Kamar')
@section('page-title', 'Edit Kamar')

@section('content')
<div class="row justify-content-center">
<div class="col-md-6">
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><strong>Edit Kamar — {{ $kamar->nama_kamar }}</strong></div>
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
        @endif
        <form method="POST" action="{{ route('admin.kamar.update', $kamar) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-7">
                    <label class="form-label">Nama Kamar <span class="text-danger">*</span></label>
                    <input type="text" name="nama_kamar" class="form-control" value="{{ old('nama_kamar', $kamar->nama_kamar) }}" required>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Kelas <span class="text-danger">*</span></label>
                    <select name="kelas" class="form-select" required>
                        @foreach(['VIP','Kelas 1','Kelas 2','Kelas 3','ICU'] as $k)
                        <option value="{{ $k }}" {{ $kamar->kelas==$k?'selected':'' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tarif per Hari <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="tarif_per_hari" class="form-control" value="{{ old('tarif_per_hari', $kamar->tarif_per_hari) }}" min="0" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kapasitas <span class="text-danger">*</span></label>
                    <input type="number" name="kapasitas" class="form-control" value="{{ old('kapasitas', $kamar->kapasitas) }}" min="1" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        @foreach(['tersedia','terisi','maintenance'] as $s)
                        <option value="{{ $s }}" {{ $kamar->status==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $kamar->keterangan) }}</textarea>
                </div>
                <div class="col-12 mt-2">
                    <button type="submit" class="btn btn-warning"><i class="fa fa-save me-1"></i>Update</button>
                    <a href="{{ route('admin.kamar.index') }}" class="btn btn-secondary ms-2">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
