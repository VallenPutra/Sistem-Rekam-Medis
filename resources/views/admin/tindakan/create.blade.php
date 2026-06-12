@extends('layouts.app')
@section('title', 'Tambah Tindakan')
@section('page-title', 'Tambah Tindakan')

@section('content')
<div class="row justify-content-center">
<div class="col-md-6">
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><strong>Form Tambah Tindakan</strong></div>
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
        @endif
        <form method="POST" action="{{ route('admin.tindakan.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama Tindakan <span class="text-danger">*</span></label>
                <input type="text" name="nama_tindakan" class="form-control" value="{{ old('nama_tindakan') }}" required placeholder="Pasang Infus, Jahit Luka...">
            </div>
            <div class="mb-3">
                <label class="form-label">Tarif <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" name="tarif" class="form-control" value="{{ old('tarif', 0) }}" min="0" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i>Simpan</button>
            <a href="{{ route('admin.tindakan.index') }}" class="btn btn-secondary ms-2">Batal</a>
        </form>
    </div>
</div>
</div>
</div>
@endsection
