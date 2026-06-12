@extends('layouts.app')
@section('title', 'Edit Tindakan')
@section('page-title', 'Edit Tindakan')

@section('content')
<div class="row justify-content-center">
<div class="col-md-6">
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><strong>Edit Tindakan — {{ $tindakan->kode_tindakan }}</strong></div>
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
        @endif
        <form method="POST" action="{{ route('admin.tindakan.update', $tindakan) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nama Tindakan <span class="text-danger">*</span></label>
                <input type="text" name="nama_tindakan" class="form-control" value="{{ old('nama_tindakan', $tindakan->nama_tindakan) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tarif <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" name="tarif" class="form-control" value="{{ old('tarif', $tindakan->tarif) }}" min="0" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $tindakan->keterangan) }}</textarea>
            </div>
            <button type="submit" class="btn btn-warning"><i class="fa fa-save me-1"></i>Update</button>
            <a href="{{ route('admin.tindakan.index') }}" class="btn btn-secondary ms-2">Batal</a>
        </form>
    </div>
</div>
</div>
</div>
@endsection
